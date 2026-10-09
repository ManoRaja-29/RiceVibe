<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Post;
use App\Models\PregnancyStage;
use App\Models\ProfileItem;
use App\Models\Service;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Starter content: doctor details supplied by the clinic plus general educational articles and service text. */
class ContentSeeder extends Seeder
{
    private function body(string ...$paras): string
    {
        return implode('', array_map(fn ($p) => str_starts_with($p, '<') ? $p : "<p>{$p}</p>", $paras));
    }

    public function run(): void
    {
        $this->categories();
        $this->posts();
        $this->services();
        $this->faqs();
        $this->pregnancy();
        $this->profile();
        $this->stories();
    }

    private function categories(): void
    {
        foreach (["Women's Health", 'Fertility', 'Pregnancy', 'Menopause', 'Surgery & Procedures'] as $i => $n) {
            Category::firstOrCreate(['slug' => Str::slug($n)], ['name' => $n, 'sort_order' => $i, 'description' => "Educational articles on ".strtolower($n).'.']);
        }
    }

    private function posts(): void
    {
        $cat = fn ($n) => Category::where('slug', Str::slug($n))->value('id');
        $disclaimer = '<p><em>This article is for general education only and is not medical advice. Please speak to a qualified doctor about your own situation.</em></p>';
        $items = [
            ['Understanding PCOS: signs, tests and care options', "Women's Health", 'Polycystic ovary syndrome is a common hormonal condition. Learn the usual signs, how it is assessed and what management may involve.', ['PCOS (polycystic ovary syndrome) is a common hormonal condition that can affect periods, ovulation and skin. Symptoms vary a lot from person to person.', '<h2>Common signs</h2><ul><li>Irregular or infrequent periods</li><li>Acne or excess hair growth</li><li>Difficulty conceiving</li></ul>', '<h2>Assessment</h2><p>Doctors usually combine a history, examination, blood tests and sometimes an ultrasound. Management is individual and often starts with lifestyle support.</p>'], true],
            ['When should you see a gynaecologist?', "Women's Health", 'Regular check-ups and some symptoms are good reasons to see a doctor. Here is a general guide.', ['Many women are unsure when a gynaecology visit is needed. Routine screening, painful or very heavy periods, unexpected bleeding and pelvic pain are common reasons.', '<h2>Reasons to see a gynaecologist</h2><ul><li>Periods that are very heavy, painful or irregular</li><li>Bleeding after intercourse or after menopause</li><li>Persistent pelvic pain</li><li>Planning a pregnancy</li></ul>'], true],
            ['Preparing for pregnancy: a preconception checklist', 'Pregnancy', 'Small steps before conception can support a healthier pregnancy. A general overview.', ['Preconception care means looking at health, medicines, vaccinations and lifestyle before trying to conceive.', '<h2>Topics to discuss with your doctor</h2><ul><li>Folic acid supplementation</li><li>Existing conditions and medicines</li><li>Vaccination status</li><li>Nutrition, activity and sleep</li></ul>'], true],
            ['Menopause: understanding the transition', 'Menopause', 'Hot flushes, sleep changes and mood shifts are common. Learn what is normal and when to seek help.', ['Menopause is the point when periods have stopped for twelve months. The years leading up to it, perimenopause, can bring varied symptoms.', '<p>Treatment options range from lifestyle measures to hormone and non-hormone therapies, chosen after discussing benefits and risks with your doctor.</p>'], false],
            ['First trimester (weeks 1–12): what to expect and how to care for yourself', 'Pregnancy', 'Early signs, common symptoms, supplements and the first antenatal visits explained in plain language.', ['The first trimester runs from week 1 to week 12. It includes conception, implantation of the embryo in the uterus and the formation of the placenta and main organs.', '<h2>Common experiences</h2><ul><li>Missed period, tender breasts and tiredness</li><li>Nausea and frequent urination</li><li>Mood changes</li></ul>', '<h2>Care in the first trimester</h2><p>Pregnancy is usually confirmed with a urine test and a scan. Folic acid, a balanced diet, booking blood tests and early antenatal counselling are commonly advised.</p>'], false],
            ['Second trimester (weeks 13–28): growth, movements and scans', 'Pregnancy', 'What changes in the middle of pregnancy, when you may feel your baby move and which scans and screening tests are common.', ['The second trimester runs from week 13 to week 28. Many mothers feel more energetic as nausea settles, and the baby’s movements are usually felt around the middle of this period.', '<h2>Checks you may be offered</h2><ul><li>Detailed anomaly scan, usually between 18 and 22 weeks</li><li>Routine blood tests and blood sugar screening</li><li>Blood pressure and growth monitoring</li></ul>', '<p>Gentle exercise, good posture and antenatal classes can help with backache and prepare you for birth.</p>'], false],
            ['Third trimester (weeks 29–40): preparing for birth', 'Pregnancy', 'Check-ups, movement awareness, birth planning and warning signs in the last stage of pregnancy.', ['The third trimester runs from week 29 to week 40. The baby gains weight steadily, and a baby is considered full term from 37 completed weeks.', '<h2>Preparing for birth</h2><ul><li>Regular check-ups and growth monitoring</li><li>Choices for delivery and a birth plan</li><li>Hospital bag and baby-care preparation</li></ul>', '<h2>When to seek help</h2><p>Contact your doctor or hospital promptly for heavy bleeding, severe pain, leaking fluid, reduced baby movements or regular strong contractions.</p>'], false],
            ['Pregnancy week by week: weeks 1 to 40 at a glance', 'Pregnancy', 'Hover over (or tap) any week from 1 to 40 to see what is happening for your baby and for you.', ['Hover over a week — or tap it on a phone — to see a short summary of how your baby is growing and what you may notice. Weeks are counted from the first day of your last period, and every pregnancy is different.', $this->weekGrid()], true],
            ['Fertility basics: when to seek an assessment', 'Fertility', 'A plain-language look at how fertility assessment usually begins for couples.', ['Couples are generally advised to seek an assessment after about twelve months of trying, or sooner if the woman is over 35 or there are known concerns.', '<p>An assessment typically involves both partners and may include hormone tests, an ultrasound and a semen analysis.</p>'], false],
            ['Minimally invasive and robotic gynaecological surgery: an overview', 'Surgery & Procedures', 'What keyhole and robotic-assisted surgery are, and why some patients may be offered them.', ['Minimally invasive surgery uses small incisions and a camera. Robotic-assisted systems let the surgeon control instruments from a console.', '<p>Whether it is suitable depends on the condition, anatomy and the surgeon’s advice. Benefits and risks should always be discussed individually.</p>'], false],
        ];

        foreach ($items as $i => [$title, $c, $excerpt, $paras, $featured]) {
            $body = $this->body(...$paras).$disclaimer;
            Post::firstOrCreate(['slug' => Str::slug($title)], [
                'category_id' => $cat($c),
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $body,
                'image_alt' => $title,
                'status' => 'published',
                'published_at' => now()->subDays(3 + $i * 6),
                'is_featured' => $featured,
                'reading_time' => reading_time($body),
                'meta_description' => $excerpt,
            ]);
        }
    }

    private function weekGrid(): string
    {
        $out = '';
        foreach ([[1, 12, 'First trimester (weeks 1–12)'], [13, 28, 'Second trimester (weeks 13–28)'], [29, 40, 'Third trimester (weeks 29–40)']] as [$a, $b, $h]) {
            $out .= "<h2>{$h}</h2><div class=\"wk-grid\">";
            for ($w = $a; $w <= $b; $w++) {
                [$title, $baby, $mother] = config("pregnancy_weeks.{$w}");
                $out .= "<div class=\"wk\" tabindex=\"0\"><b>{$w}</b><small>Week</small><div class=\"wk-d\"><strong>Week {$w} · {$title}</strong><p><em>Baby:</em> {$baby}</p><p><em>Mother:</em> {$mother}</p></div></div>";
            }
            $out .= '</div>';
        }

        return $out;
    }
    private function services(): void
    {
        $data = [
            'fertility' => [
                ['Fertility assessment', 'search', 'A structured evaluation to understand factors affecting conception.', 'Assessment usually includes a detailed history, examination, hormone blood tests, pelvic ultrasound and, for the partner, a semen analysis. The aim is to identify treatable factors and plan next steps.'],
                ['PCOS care', 'flower', 'Personalised management of polycystic ovary syndrome.', 'Care may include lifestyle guidance, cycle regulation, ovulation support and long-term health monitoring, tailored to your goals.'],
                ['Fertility treatments', 'sparkle', 'An overview of treatment pathways, chosen individually.', 'Depending on the findings, options may include ovulation induction, intrauterine insemination or assisted reproduction referral. Your doctor will explain the suitability, benefits and risks of each.'],
            ],
            'gynaecology' => [
                ['Menstrual disorders', 'drop', 'Heavy, painful or irregular periods.', 'Assessment looks for causes such as hormonal imbalance, fibroids, polyps or endometriosis, followed by medical or procedural options.'],
                ['Fibroids, cysts and endometriosis', 'heart', 'Evaluation and management of common gynaecological conditions.', 'Treatment depends on symptoms, size, age and fertility plans, and may range from observation to medication or surgery.'],
                ['Gynaecological procedures & surgery', 'scope', 'Including minimally invasive approaches where suitable.', 'Procedures may include hysteroscopy, laparoscopy and, where available, robotic-assisted surgery. Suitability is decided after assessment.'],
                ['Menopause care', 'sun', 'Support through perimenopause and beyond.', 'Care covers symptom relief, bone and heart health discussions and screening, with individualised treatment choices.'],
            ],
            'maternity' => [
                ['Antenatal (prenatal) care', 'baby', 'Regular check-ups, scans and guidance through pregnancy.', 'Visits monitor you and your baby, with screening tests, nutrition advice and birth planning at suitable stages.'],
                ['High-risk pregnancy care', 'shield', 'Close monitoring for pregnancies needing extra attention.', 'Pregnancies with conditions such as diabetes, hypertension or previous complications may need closer follow-up and a tailored care plan.'],
                ['Delivery: normal birth & C-section', 'calendar', 'Painless normal delivery options and C-section management.', 'Birth plans are individualised and may change according to medical needs. Your doctor will discuss options, pain relief and safety considerations.'],
                ['Postpartum care', 'leaf', 'Recovery, feeding support and emotional wellbeing.', 'A postnatal review checks recovery, contraception, mood and any concerns, and signposts further support.'],
            ],
        ];
        foreach ($data as $section => $list) {
            foreach ($list as $i => [$title, $icon, $summary, $text]) {
                Service::firstOrCreate(['slug' => Str::slug($title)], [
                    'section' => $section, 'title' => $title, 'icon' => $icon, 'summary' => $summary,
                    'body' => '<p>'.$text.'</p>',
                    'sort_order' => $i,
                ]);
            }
        }
    }

    private function faqs(): void
    {
        $rows = [
            ['How do I contact the clinic?', 'Call 073393 38888, message on WhatsApp, or use the enquiry form on the Contact page. The clinic is at P 39, Ground floor, Third Main Road, 6th Ave, Anna Nagar, Chennai 600040.'],
            ['What should I bring to my first visit?', 'Bring previous reports, a list of current medicines and any questions you have. For period concerns, a note of recent cycle dates is helpful.'],
            ['Is the information on this website medical advice?', 'No. Articles are educational and cannot replace an individual medical assessment.'],
            ['When should I seek an infertility assessment?', 'Generally after 12 months of trying (6 months if over 35), or earlier if there are known concerns. Your doctor can advise.'],
            ['What is robotic-assisted surgery?', 'A form of minimally invasive surgery where the surgeon controls instruments through a console. Suitability depends on your condition and will be discussed with you.'],
        ];
        foreach ($rows as $i => [$q, $a]) {
            Faq::firstOrCreate(['question' => $q], ['answer' => $a, 'sort_order' => $i]);
        }
    }

    private function pregnancy(): void
    {
        $rows = [
            ['Planning', 'Before conception', 'baby', 'Preconception review, folic acid, health check.'],
            ['First trimester', 'Weeks 1–12', 'sparkle', 'Confirmation scan, booking tests and early-pregnancy guidance.'],
            ['Second trimester', 'Weeks 13–28', 'flower', 'Anomaly scan, screening and monitoring growth.'],
            ['Third trimester', 'Weeks 29–40', 'calendar', 'Growth checks and birth planning.'],
            ['Delivery', 'Birth', 'heart', 'Supportive, individualised care during labour and birth.'],
            ['Postpartum', 'After birth', 'leaf', 'Recovery, feeding support and emotional wellbeing.'],
        ];
        foreach ($rows as $i => [$t, $l, $icon, $s]) {
            PregnancyStage::firstOrCreate(['title' => $t], ['label' => $l, 'icon' => $icon, 'summary' => $s, 'sort_order' => $i]);
        }
    }

    private function stories(): void
    {
        $src = 'Verified patient review';
        $rows = [
            ['Sharmila G.', 'Mirena (hormonal IUD)', 'The quality of my life after Mirena (IUD) had drastically changed. Period cramps and heavy flow were my problems. I had never stepped out of my house during periods. Now after so many years of suffering I felt what normal periods are like. I have been using it for 7 years now. Dr. Indra Nedumaran, I am grateful to you for suggesting Mirena.'],
            ['Ranjani', 'Labour and delivery', 'Indira mam is very supportive. She has always been practical enough to explain the issues and address them with the right solution. She answered all the queries and doubts which my husband and I had. I tested COVID positive in my 8th month, and with her help and mental support during home quarantine we have been blessed with a baby girl.'],
            ['Sripriya K H.', 'Normal vaginal delivery', 'Doctor is friendly and extremely patient in listening. Answers queries in a way understandable to a layperson. Had my two daughters delivered normally under her supervision. She also organizes prenatal and postnatal classes to help one stay informed of the choices, baby care and exercises.'],
            ['Uma', 'Pap smear', 'Dr Indira is a very humble person who embraces the patient with her smile and care. The clinic addresses all the needs before pregnancy and after delivery, from foot reflexology for mood swings to yoga, meditation and physio exercises, with periodic meetings with expert doctors of various disciplines.'],
            ['Saranya M.', 'Pregnancy check-up', 'Dr. Indra is not only highly knowledgeable and professional but also truly empathetic. She takes the time to explain each part of the process, every test and every small detail in a way that is easy to understand and comforting. The clinic is very clean and organised, and the staff are warm and helpful.'],
            ['Mrs Divya J.', 'Pregnancy check-up', 'Since the time of pregnancy confirmation I have been visiting Indra mam and am very satisfied with the way she cares for the patient’s health. She always gives moral support, gives clarity about the problems we face during pregnancy and never hesitates to clear our doubts.'],
        ];
        foreach ($rows as $i => [$name, $visit, $body]) {
            Story::firstOrCreate(['author_name' => $name, 'visit_for' => $visit], ['body' => $body, 'source' => $src, 'sort_order' => $i]);
        }
    }

    private function profile(): void
    {
        $rows = [
            ['qualification', 'MBBS', 'Rajah Muthiah Medical College', '1998', ''],
            ['qualification', 'Fellowship in Emergency Medicine (FEM)', 'Apollo Hospitals & Research Foundation', '2001', ''],
            ['qualification', 'DNB (Obstetrics & Gynaecology)', 'CSI Rainy Multi-Specialty Hospital', '2006', ''],
            ['qualification', 'Diploma in Advanced Life Support (ALS)', 'Germany', '', ''],
            ['registration', 'Tamil Nadu Medical Council', 'Reg. No. 60859', '', ''],
            ['timeline', 'PRHO', 'Raja Muttiah Medical College & Hospital, Annamalai University, Chidambaram', '1997 – 1998', ''],
            ['timeline', 'MBBS completed', 'Rajah Muthiah Medical College', '1998', ''],
            ['timeline', 'Physician', 'Annamalai University, Chidambaram', '1998 – 2000', ''],
            ['timeline', 'Resident Doctor', 'Apollo Hospital', '2000', ''],
            ['timeline', 'Trainee – Emergency', 'Apollo Hospital', '2000 – 2001', ''],
            ['timeline', 'C.M.O. · Fellowship in Emergency Medicine', 'Apollo Hospitals & Research Foundation', '2001 – 2002', ''],
            ['timeline', 'S.H.O.', 'CSI Rainy Multispeciality Hospital', '2002', ''],
            ['timeline', 'Registrar, then Senior Registrar', 'CSI Rainy Multispeciality Hospital', '2005 – 2006', ''],
            ['timeline', 'DNB Obstetrics & Gynaecology · Junior Consultant', 'CSI Rainy Multispeciality Hospital', '2006', ''],
            ['timeline', 'Trainee – Urogynaecology', 'Govt. Kasturba Gandhi Hospital', '2007', ''],
            ['timeline', 'Tutor', 'Adiparasakthi Hospital and Research Institute', '2007', ''],
            ['timeline', 'Consultant', 'Billroth Hospital', '2007 – 2016', ''],
            ['timeline', 'Senior Consultant Obstetrician & Gynaecologist', 'VS Hospitals, Kilpauk, Chennai', 'Current', ''],
            ['timeline', 'Founder & Director', 'A2Z Pregnancy Care, Anna Nagar, Chennai', 'Current', ''],
            ['achievement', 'Urvashi Pshaw Award in Gynaecology', '', '2013', ''],
            ['membership', 'Indian Medical Association (IMA)', '', '', ''],
            ['membership', 'Obstetric and Gynaecological Society of Southern India (OGSSI)', '', '', ''],
            ['membership', 'Federation of Obstetric and Gynaecological Societies of India (FOGSI)', '', '', ''],
            ['membership', 'Indian Society for Assisted Reproduction (ISAR)', '', '', ''],
        ];
        foreach ($rows as $i => [$type, $title, $sub, $period, $desc]) {
            ProfileItem::firstOrCreate(['title' => $title, 'type' => $type], ['subtitle' => $sub, 'period' => $period, 'description' => $desc, 'sort_order' => $i, 'is_placeholder' => false]);
        }
    }
}
