<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing settings if any to avoid duplication
        SiteSetting::query()->delete();

        $site = SiteSetting::create([
            'name' => [
                'ar' => 'مدارس بوابة الأندلس الأهلية والعالمية',
                'en' => 'Al-Andalus Private & International Schools',
            ],
            'theme' => 'template1',
            'description' => [
                'ar' => 'مدارس بوابة الأندلس الأهلية والعالمية بالمملكة العربية السعودية - صرح تعليمي رائد تأسس عام 1404هـ (1984م)، يهدف لبناء جيل رائد يمتلك قدرات تعلم عالمية عبر مجمعات تعليمية متميزة في جدة ومكة المكرمة والطائف وأبها وخميس مشيط والجوف ونجران.',
                'en' => 'Al-Andalus Private & International Schools in Saudi Arabia - A leading educational institution established in 1984 (1404H), aiming to build a pioneering generation with global learning capabilities across campuses in Jeddah, Makkah, Taif, Abha, Khamis Mushait, Jouf, and Najran.',
            ],
            'color_primary' => '#25376F',
            'color_secondary' => '#FFFEFC',
            'color_accent' => '#25376F',
            'phone' => '920020282',
            'email' => 'crm@as.edu.sa',
            'address' => [
                'ar' => 'المملكة العربية السعودية - المقر الرئيسي: جدة (حي الزهراء - حي الشاطئ - حي الفيحاء - حي المنار - حي الحمدانية - أبحر) وفروعنا في مكة المكرمة، الطائف، أبها، خميس مشيط، الجوف، ونجران.',
                'en' => 'Kingdom of Saudi Arabia - HQ: Jeddah (Al-Zahra, Al-Shatie, Al-Fayhaa, Al-Manar, Al-Hamdaniya, Obhur) and branches in Makkah, Taif, Abha, Khamis Mushait, Jouf, and Najran.',
            ],
            'about_us' => [
                'ar' => '<div class="about-us-content">
                    <h2>عن مدارس بوابة الأندلس الأهلية والعالمية</h2>
                    <p>تُعد مدارس الأندلس الأهلية والعالمية صرحاً تعليمياً وتربوياً رادئاً في المملكة العربية السعودية، حيث تأسست عام 1404هـ (1984م)، واستطاعت عبر مسيرتها الحافلة أن تكون واجهة لتحفيز الإبداع والتميز التربوي والتعليمي لبناء مستقبل أفضل لأبنائنا وبناتنا الطلاب.</p>
                    
                    <h3>رؤيتنا</h3>
                    <p>منظومة تعليمية رائدة، تبني جيلاً يمتلك قدرات تعلم عالمية.</p>

                    <h3>رسالتنا</h3>
                    <p>بناء جيل رائد يُحب التعلم، يرعاه فريق مُعد في مؤسسة تربوية معاصرة، تحكمها قيم أصيلة، وتلبي تطلعات المجتمع.</p>

                    <h3>قيمنا المحورية</h3>
                    <ul>
                        <li><strong>الأصالة والاعتزاز بالهوية:</strong> التمسك بقيمنا الإسلامية والهوية الوطنية السعودية.</li>
                        <li><strong>الإبداع والابتكار:</strong> تحفيز مهارات التفكير الناقد والابتكار والذكاء الرقمي.</li>
                        <li><strong>التميز الأكاديمي:</strong> تقديم مناهج وبرامج إثرائية متطورة وفق أفضل المعايير الدولية والمحلية.</li>
                        <li><strong>التطوير المستمر:</strong> التأهيل المستمر للكادر التعليمي والإداري وتحديث البيئة التعليمية.</li>
                        <li><strong>المواطنة والمسؤولية المجتمعية:</strong> غرس حس المسؤولية والانتماء لدى أبنائنا وبناتنا.</li>
                    </ul>

                    <h3>أرقام وإنجازات الأندلس</h3>
                    <ul>
                        <li>أكثر من 28,000 طالب وطالبة في مختلف المراحل التعليمية.</li>
                        <li>أكثر من 1,200 معلم وإداري ذوي خبرات عالية.</li>
                        <li>15 فرع ومجمع تعليمي تنتشر في 8 مدن رئيسية بالمملكة.</li>
                        <li>اعتمادات أكاديمية محلية ودولية متميزة.</li>
                        <li>مسارات تعليمية متنوعة: مسارات أهلية، عالمية، وبرامج الموهوبين.</li>
                    </ul>
                </div>',
                'en' => '<div class="about-us-content">
                    <h2>About Al-Andalus Private & International Schools</h2>
                    <p>Al-Andalus Private & International Schools is a leading educational institution in the Kingdom of Saudi Arabia, established in 1984 (1404H). Over its rich history, it has served as a beacon for inspiring creativity and educational excellence to build a better future for our students.</p>
                    
                    <h3>Our Vision</h3>
                    <p>A leading educational system building a generation with global learning capabilities.</p>

                    <h3>Our Mission</h3>
                    <p>Building a pioneering generation that loves learning, nurtured by a prepared team in a contemporary educational institution governed by authentic values and meeting society\'s aspirations.</p>

                    <h3>Our Core Values</h3>
                    <ul>
                        <li><strong>Authenticity & Identity:</strong> Adhering to Islamic values and Saudi national identity.</li>
                        <li><strong>Innovation & Creativity:</strong> Fostering critical thinking, innovation, and digital fluency.</li>
                        <li><strong>Academic Excellence:</strong> Delivering advanced curricula and enrichment programs according to top standards.</li>
                        <li><strong>Continuous Improvement:</strong> Ongoing professional development for staff and upgrading learning environments.</li>
                        <li><strong>Citizenship & Responsibility:</strong> Instilling a sense of responsibility and belonging in our students.</li>
                    </ul>

                    <h3>Key Highlights & Achievements</h3>
                    <ul>
                        <li>Over 28,000 male and female students across all educational stages.</li>
                        <li>Over 1,200 highly qualified teachers and administrators.</li>
                        <li>15 branches and educational complexes across 8 major cities in KSA.</li>
                        <li>Top local and international academic accreditations.</li>
                        <li>Diverse educational tracks: National, International, and Gifted Student Programs.</li>
                    </ul>
                </div>',
            ],
            'shipping_returns' => [
                'ar' => '<div class="shipping-returns-content">
                    <h2>سياسة القبول والتسجيل والانسحاب</h2>
                    
                    <h3>شروط وأحكام التسجيل</h3>
                    <ul>
                        <li>يتم تقديم طلبات التسجيل الإلكتروني عبر المنصة أو زيارة أحد مجمعات مدارس الأندلس.</li>
                        <li>يستوجب استكمال الملف الإداري والصحي للطالب وتقديم كافة الأوراق والمستندات المطلوبة والمصدقة من وزارة التعليم.</li>
                        <li>يخضع الطلاب لاختبارات تحديد المستوى والمقابلات الشخصية حسب المرحلة الدراسية.</li>
                    </ul>

                    <h3>سياسة الرسوم الدراسية وانسحاب الطلاب</h3>
                    <ul>
                        <li>تحدد الرسوم الدراسية لكل مرحلة وفرع وفق القرارات الصادرة عن إدارة المدارس واعتمادات وزارة التعليم.</li>
                        <li>في حال انسحاب الطالب قبل بداية العام الدراسي، يتم استرداد الرسوم المدفوعة بعد خصم رسوم التسجيل والإجراءات الإدارية.</li>
                        <li>عند الانسحاب خلال الأسابيع الأولى من الفصل الدراسي، تطبق اللوائح المنظمة المعتمدة من وزارة التعليم للانسحاب والاسترداد الجزئي.</li>
                    </ul>
                </div>',
                'en' => '<div class="shipping-returns-content">
                    <h2>Admission, Registration & Withdrawal Policy</h2>
                    
                    <h3>Admission & Registration Terms</h3>
                    <ul>
                        <li>Registration requests can be submitted online via the platform or by visiting Al-Andalus school complexes.</li>
                        <li>Complete administrative and health files along with required documents approved by the Ministry of Education are required.</li>
                        <li>Students undergo placement tests and personal interviews depending on the grade level.</li>
                    </ul>

                    <h3>Tuition Fees & Student Withdrawal Policy</h3>
                    <ul>
                        <li>Tuition fees for each grade and branch are determined in accordance with school administration decisions and Ministry of Education approvals.</li>
                        <li>In case of withdrawal prior to the start of the academic year, paid fees are refunded after deducting registration and administrative processing fees.</li>
                        <li>If withdrawal occurs during the first weeks of the semester, Ministry of Education regulations for withdrawal and partial refunds apply.</li>
                    </ul>
                </div>',
            ],
            'privacy_policy' => [
                'ar' => '<div class="privacy-policy-content">
                    <h2>سياسة الخصوصية وحماية البيانات</h2>
                    <p><em>آخر تحديث: 2026</em></p>

                    <h3>مقدمة</h3>
                    <p>تلتزم إدارة مدارس بوابة الأندلس الأهلية والعالمية بحماية خصوصية الطلاب وأولياء الأمور والمستخدمين لمنصتنا الإلكترونية، وفقاً للأنظمة واللوائح المعمول بها في المملكة العربية السعودية وضوابط الهيئة الوطنية للأمن السيبراني.</p>

                    <h3>البيانات التي نجمعها</h3>
                    <ul>
                        <li><strong>بيانات الطالب وولي الأمر:</strong> الاسم، الهوية الوطنية/الإقامة، تاريخ الميلاد، رقم التواصل، البريد الإلكتروني، والعنوان.</li>
                        <li><strong>البيانات الأكاديمية والتعليمية:</strong> النتائج الدراسية، السجلات الأكاديمية، الحضور والغياب، وتقارير الاختبارات والواجبات.</li>
                        <li><strong>بيانات الاستخدام الرقمي:</strong> سجلات تسجيل الدخول إلى المنصة، والتفاعل مع الدروس الإلكترونية والأنشطة.</li>
                    </ul>

                    <h3>أغراض استخدام البيانات</h3>
                    <ul>
                        <li>إدارة العملية التعليمية والتربوية ومتابعة المستوى الأكاديمي للطالب.</li>
                        <li>التواصل المباشر مع أولياء الأمور بشأن التقارير والإشعارات المدرسية.</li>
                        <li>تقديم الخدمات الرقمية عبر المنصة وتأمين حسابات المستخدمين.</li>
                        <li>الالتزام بالمتطلبات والتوجيهات التنظيمية الصادرة عن وزارة التعليم والجهات الرسمية في المملكة.</li>
                    </ul>

                    <h3>سرية البيانات وحمايتها</h3>
                    <p>نطبق أعلى المعايير التقنية والأمنية لتشفير البيانات وحمايتها من أي وصول غير مصرح به. ولا يتم مشاركة أي بيانات مع أطراف خارجية إلا وفق الأنظمة والأحكام القانونية الرسمية.</p>

                    <h3>التواصل معنا</h3>
                    <p>لأي استفسارات حول الخصوصية وحماية البيانات، يمكنكم التواصل مع مركز خدمة العملاء:<br>
                    الرقم الموحد: 920020282<br>
                    البريد الإلكتروني: crm@as.edu.sa</p>
                </div>',
                'en' => '<div class="privacy-policy-content">
                    <h2>Privacy Policy & Data Protection</h2>
                    <p><em>Last Updated: 2026</em></p>

                    <h3>Introduction</h3>
                    <p>Al-Andalus Private & International Schools is committed to protecting the privacy of students, parents, and platform users in accordance with Saudi Arabian regulations and National Cybersecurity Authority standards.</p>

                    <h3>Information We Collect</h3>
                    <ul>
                        <li><strong>Student & Parent Information:</strong> Full name, National ID/Iqama, birth date, contact phone, email, and address.</li>
                        <li><strong>Academic & Educational Data:</strong> Grades, academic records, attendance logs, exam and assignment reports.</li>
                        <li><strong>Digital Usage Data:</strong> Platform login logs, interactions with digital lessons, and online activities.</li>
                    </ul>

                    <h3>Purpose of Data Usage</h3>
                    <ul>
                        <li>Managing educational processes and monitoring student academic progress.</li>
                        <li>Direct communication with parents regarding progress reports and school notifications.</li>
                        <li>Providing digital services and securing user accounts.</li>
                        <li>Complying with Ministry of Education regulations and official KSA mandates.</li>
                    </ul>

                    <h3>Data Confidentiality & Protection</h3>
                    <p>We implement stringent technical and security standards to encrypt data and prevent unauthorized access. Data is never shared with third parties except under formal legal and regulatory requirements.</p>

                    <h3>Contact Us</h3>
                    <p>For any inquiries regarding data protection and privacy, please contact customer support:<br>
                    Unified Phone: 920020282<br>
                    Email: crm@as.edu.sa</p>
                </div>',
            ],
            'terms_and_conditions' => [
                'ar' => '<div class="terms-conditions-content">
                    <h2>الشروط والأحكام لاستخدام المنصة التعليمية</h2>
                    <p><em>آخر تحديث: 2026</em></p>

                    <h3>مقدمة</h3>
                    <p>مرحباً بكم في المنصة الإلكترونية لمدارس بوابة الأندلس الأهلية والعالمية. استخدامكم لهذه المنصة يُعد موافقة كاملة على الالتزام بالشروط والأحكام المبينة أدناه.</p>

                    <h3>ضوابط استخدام حساب المنصة</h3>
                    <ul>
                        <li>تُخصص الحسابات الإلكترونية للطلاب والكادر التعليمي وأولياء الأمور لاستخدامها في الأغراض التعليمية والتربوية المعتمدة فقط.</li>
                        <li>المستخدم مسؤول مسؤولية كاملة عن المحافظة على سرية بيانات الدخول وكلمة المرور وعدم مشاركتها مع أي طرف آخر.</li>
                        <li>يُحظر تماماً محاولة الوصول غير المصرح به للنظام أو التعديل في المحتوى التعليمي أو السجلات الأكاديمية.</li>
                    </ul>

                    <h3>الملكية الفكرية وحقوق النشر</h3>
                    <p>جميع المناهج الدراسية، الاختبارات، المواد الإثرائية، والمحتوى المرئي والمكتب المتاح على المنصة هي ملك حصري لمدارس الأندلس الأهلية والعالمية ومحمية بموجب نظام حماية حقوق المؤلف في المملكة العربية السعودية.</p>

                    <h3>الالتزام بالسلوك الرقمي والانضباط</h3>
                    <ul>
                        <li>يجب على الطلاب الالتزام بقواعد السلوك الرقمي والانضباط المدرسي أثناء المشاركة في الحصص والأنشطة عبر المنصة.</li>
                        <li>تحتفظ إدارة المدارس بحق اتخاذ الإجراءات النظامية والإدارية في حال ارتكاب أي مخالفات سلوكية أو رقمية.</li>
                    </ul>

                    <h3>التعديلات والتحديثات</h3>
                    <p>تحتفظ مدارس الأندلس بحق تحديث وتعديل هذه الشروط والأحكام عند الحاجة، وتصبح التعديلات نافذة فور نشرها على المنصة.</p>
                </div>',
                'en' => '<div class="terms-conditions-content">
                    <h2>Terms and Conditions for Platform Use</h2>
                    <p><em>Last Updated: 2026</em></p>

                    <h3>Introduction</h3>
                    <p>Welcome to the digital platform of Al-Andalus Private & International Schools. By using this platform, you agree to comply with the terms and conditions outlined below.</p>

                    <h3>Account Usage Rules</h3>
                    <ul>
                        <li>Accounts assigned to students, parents, and educators are strictly for authorized educational purposes.</li>
                        <li>Users are solely responsible for maintaining the confidentiality of credentials and passwords.</li>
                        <li>Unauthorized access attempts, content modifications, or altering academic records are strictly prohibited.</li>
                    </ul>

                    <h3>Intellectual Property & Copyright</h3>
                    <p>All educational materials, exams, enrichment content, audio-visual resources, and publications on the platform are the exclusive property of Al-Andalus Schools and protected under Saudi Copyright Law.</p>

                    <h3>Digital Conduct & Discipline</h3>
                    <ul>
                        <li>Students must adhere to digital code of conduct and school discipline guidelines during online sessions and activities.</li>
                        <li>School administration reserves the right to take administrative or regulatory action in cases of policy violations.</li>
                    </ul>

                    <h3>Amendments</h3>
                    <p>Al-Andalus Schools reserves the right to update these terms when necessary, and revisions take immediate effect upon posting.</p>
                </div>',
            ],
            'refund_policy' => [
                'ar' => '<div class="refund-policy-content">
                    <h2>سياسة القبول والاسترداد</h2>
                    <p>تسري الأحكام والضوابط المنظمة للاسترداد وتأكيد التسجيل وفق اللوائح المعتمدة من إدارة مدارس الأندلس الأهلية والعالمية ووزارة التعليم بالمملكة العربية السعودية.</p>
                </div>',
                'en' => '<div class="refund-policy-content">
                    <h2>Admission & Refund Policy</h2>
                    <p>Regulations governing registration confirmation and refunds apply in accordance with approved rules from Al-Andalus Schools Administration and the Ministry of Education in Saudi Arabia.</p>
                </div>',
            ],
            'shipping_policy' => [
                'ar' => '<div class="shipping-policy-content">
                    <h2>خدمات التوصيل والنقل المدرسي</h2>
                    <p>توفر مدارس الأندلس الأهلية والعالمية خدمة النقل المدرسي والأنشطة وفق أعلى معايير السلامة والجودة لتغطية كافة الأحياء والمناطق المحيطة بالمجمعات التعليمية في مدن المملكة.</p>
                </div>',
                'en' => '<div class="shipping-policy-content">
                    <h2>School Bus & Transportation Services</h2>
                    <p>Al-Andalus Private & International Schools provides safe school bus transportation services covering all neighborhoods and surrounding areas around educational complexes in KSA cities.</p>
                </div>',
            ],
        ]);

        if (file_exists(base_path('public/logo.svg'))) {
            $site->addMedia(base_path('public/logo.svg'))->preservingOriginal()->toMediaCollection('logo_white', 'public');
            $site->addMedia(base_path('public/logo.svg'))->preservingOriginal()->toMediaCollection('logo_black', 'public');
        }
        if (file_exists(base_path('public/favicon.svg'))) {
            $site->addMedia(base_path('public/favicon.svg'))->preservingOriginal()->toMediaCollection('favicon', 'public');
        }
    }
}
