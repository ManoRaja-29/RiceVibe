<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Support\Seo;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $term = trim((string) $request->query('q'));
        $topic = in_array($request->query('topic'), Question::TOPICS, true) ? $request->query('topic') : null;

        $query = Question::published();
        if ($topic) {
            $query->where('topic', $topic);
        }
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('body', 'like', $like)->orWhere('answer', 'like', $like));
        }

        $faqs = \App\Models\Faq::published()->limit(8)->get();
        $faqSchema = $faqs->isEmpty() ? [] : [[
            '@context' => 'https://schema.org', '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => ['@type' => 'Question', 'name' => $f->question, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer]])->all(),
        ]];

        return view('qa.index', [
            'faqs' => $faqs,
            'questions' => $query->paginate(10)->withQueryString(),
            'term' => $term, 'topic' => $topic,
            'seo' => Seo::make('Consult Q&A', 'Questions from patients answered by '.setting('doctor_name').'. General educational information on women’s health, fertility and pregnancy.', null, 'website', $faqSchema, null, noindex: $term !== ''),
        ]);
    }

    public function show(int $id)
    {
        $question = Question::published()->findOrFail($id);
        $question->increment('views');

        return view('qa.show', [
            'question' => $question,
            'seo' => Seo::make($question->title, \Illuminate\Support\Str::limit(strip_tags($question->answer), 155), null, 'article'),
        ]);
    }

    public function ask()
    {
        return view('qa.ask', [
            'seo' => Seo::make('Ask a question', 'Send a question to '.setting('doctor_name').'. Answers are published on this page after review.'),
            'formTime' => encrypt(time()),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->filled('website')) {
            return redirect()->route('qa.ask')->with('sent', true);
        }
        try {
            $started = (int) decrypt($request->input('_ft'));
        } catch (\Throwable) {
            $started = 0;
        }
        if ($started === 0 || time() - $started < 4 || time() - $started > 7200) {
            return back()->withInput()->withErrors(['body' => 'Please wait a moment and submit the form again.']);
        }

        $data = $request->validate([
            'asker_name' => 'required|string|max:120',
            'asker_email' => 'required|email:rfc|max:190',
            'topic' => 'nullable|in:'.implode(',', Question::TOPICS),
            'title' => 'required|string|min:8|max:200',
            'body' => 'required|string|min:20|max:3000',
            'consent' => 'accepted',
        ], ['consent.accepted' => 'Please confirm you understand how your question will be used.']);

        if (substr_count(strtolower($data['body'].' '.$data['title']), 'http') > 1) {
            return back()->withInput()->withErrors(['body' => 'Please remove links from your question.']);
        }

        unset($data['consent']);
        Question::create($data + ['status' => 'pending', 'ip_address' => $request->ip()]);

        return redirect()->route('qa.ask')->with('sent', true);
    }
}
