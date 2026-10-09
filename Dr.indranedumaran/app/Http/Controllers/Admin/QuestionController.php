<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class QuestionController extends CrudController
{
    protected string $model = Question::class;

    protected string $route = 'admin.questions';

    protected string $title = 'Consult Q&A';

    protected string $singular = 'question';

    protected array $searchable = ['title', 'body', 'answer', 'asker_name'];

    protected function defaults(): array
    {
        return ['status' => 'pending'];
    }

    protected function filters(): array
    {
        return ['status' => ['label' => 'All statuses', 'options' => Question::STATUSES], 'topic' => ['label' => 'All topics', 'options' => array_combine(Question::TOPICS, Question::TOPICS)]];
    }

    protected function columns(): array
    {
        return [
            ['Question', fn ($q) => $q->title],
            ['Asked by', fn ($q) => $q->asker_name.' · '.$q->asker_email],
            ['Topic', fn ($q) => $q->topic ?: '—'],
            ['Received', fn ($q) => $q->created_at->format('d M Y')],
            ['Status', fn ($q) => $q->status, 'badge'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Question title', 'rules' => 'required|max:200'],
            ['name' => 'body', 'label' => 'Full question', 'type' => 'textarea', 'rows' => 5, 'rules' => 'required|max:3000'],
            ['name' => 'answer', 'label' => 'Doctor’s reply', 'type' => 'textarea', 'rows' => 8, 'rules' => 'nullable|max:5000', 'help' => 'Plain text. Required before a question can be published.'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => Question::STATUSES, 'rules' => 'required|in:pending,published,rejected'],
            ['name' => 'topic', 'label' => 'Topic', 'type' => 'select', 'options' => array_combine(Question::TOPICS, Question::TOPICS), 'rules' => 'nullable|in:'.implode(',', Question::TOPICS)],
            ['name' => 'asker_name', 'label' => 'Asker name (only the first name is shown publicly)', 'rules' => 'required|max:120'],
            ['name' => 'asker_email', 'label' => 'Asker email (never shown publicly)', 'rules' => 'required|email|max:190'],
        ];
    }

    protected function beforeSave(array &$data, ?Model $record): void
    {
        if (($data['status'] ?? null) === 'published' && blank($data['answer'] ?? null)) {
            back()->withInput()->withErrors(['answer' => 'Add a reply before publishing.'])->throwResponse();
        }
        if (filled($data['answer'] ?? null) && ! $record?->answered_at) {
            $data['answered_at'] = now();
        }
    }
}
