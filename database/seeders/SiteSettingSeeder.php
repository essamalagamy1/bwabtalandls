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
        $site = SiteSetting::create([
            'name' => [
                'ar' => 'منصة علّمني',
                'en' => 'Alemni Platform',
            ],
            'theme' => 'template1',
            'description' => [
                'ar' => 'منصة تعليمية مصرية رائدة تقدم كورسات احترافية في البرمجة والتقنية والتصميم والتسويق مع أفضل المدربين المصريين والعرب',
                'en' => 'A leading Egyptian educational platform offering professional courses in programming, technology, design, and marketing with the best Egyptian and Arab instructors',
            ],
            'about_us' => [
                'ar' => '<div class="about-us-content">
                    <h2>من نحن</h2>
                    <p>منصة علّمني هي منصة تعليمية مصرية رائدة تهدف إلى تمكين الشباب العربي من اكتساب المهارات التقنية والمهنية المطلوبة في سوق العمل. نقدم محتوى تعليمي عالي الجودة باللغة العربية مع أفضل المدربين المصريين والعرب.</p>
                    
                    <h3>رؤيتنا</h3>
                    <p>نسعى لأن نكون المنصة التعليمية الأولى في مصر والعالم العربي، من خلال توفير كورسات احترافية تواكب متطلبات سوق العمل بأسعار مناسبة للجميع.</p>
                    
                    <h3>قيمنا</h3>
                    <ul>
                        <li><strong>الجودة:</strong> نختار مدربينا بعناية فائقة ونراجع جميع الكورسات لضمان أعلى مستوى من الجودة</li>
                        <li><strong>التطوير المستمر:</strong> نحدث كورساتنا باستمرار لتواكب أحدث التطورات في كل مجال</li>
                        <li><strong>الدعم:</strong> فريق دعم فني متميز لمساعدتك طوال رحلتك التعليمية</li>
                        <li><strong>الوصول للجميع:</strong> نؤمن بأن التعليم الجيد حق للجميع، لذا نقدم أسعاراً تنافسية وخصومات مستمرة</li>
                    </ul>
                    
                    <h3>لماذا نحن؟</h3>
                    <p>مع خبرة تمتد لأكثر من 5 سنوات في مجال التعليم الإلكتروني، نفخر بتقديم:</p>
                    <ul>
                        <li>أكثر من 500 كورس في مختلف المجالات</li>
                        <li>أكثر من 100 مدرب محترف</li>
                        <li>أكثر من 50,000 طالب مسجل</li>
                        <li>شهادات معتمدة عند إتمام الكورسات</li>
                        <li>ضمان استرداد الأموال خلال 14 يوم</li>
                    </ul>
                </div>',
                'en' => '<div class="about-us-content">
                    <h2>About Us</h2>
                    <p>Alemni Platform is a leading Egyptian educational platform aimed at empowering Arab youth to acquire the technical and professional skills needed in the job market. We provide high-quality educational content in Arabic with the best Egyptian and Arab instructors.</p>
                    
                    <h3>Our Vision</h3>
                    <p>We strive to be the leading educational platform in Egypt and the Arab world, by providing professional courses that meet job market requirements at affordable prices for everyone.</p>
                    
                    <h3>Our Values</h3>
                    <ul>
                        <li><strong>Quality:</strong> We carefully select our instructors and review all courses to ensure the highest level of quality</li>
                        <li><strong>Continuous Development:</strong> We constantly update our courses to keep up with the latest developments in each field</li>
                        <li><strong>Support:</strong> An outstanding technical support team to help you throughout your learning journey</li>
                        <li><strong>Accessibility:</strong> We believe that good education is a right for everyone, so we offer competitive prices and ongoing discounts</li>
                    </ul>
                    
                    <h3>Why Choose Us?</h3>
                    <p>With over 5 years of experience in e-learning, we are proud to offer:</p>
                    <ul>
                        <li>Over 500 courses in various fields</li>
                        <li>Over 100 professional instructors</li>
                        <li>Over 50,000 registered students</li>
                        <li>Certified certificates upon course completion</li>
                        <li>14-day money-back guarantee</li>
                    </ul>
                </div>',
            ],
            'shipping_returns' => [
                'ar' => '<div class="shipping-returns-content">
                    <h2>سياسة الوصول والاسترداد</h2>
                    
                    <h3>الوصول للكورسات</h3>
                    <h4>طريقة الوصول:</h4>
                    <ul>
                        <li>بعد إتمام عملية الشراء، سيكون الكورس متاحاً فوراً في حسابك</li>
                        <li>يمكنك مشاهدة الكورسات من أي جهاز (كمبيوتر، موبايل، تابلت)</li>
                        <li>الوصول للكورس مدى الحياة بعد الشراء</li>
                    </ul>
                    
                    <h4>متطلبات المشاهدة:</h4>
                    <ul>
                        <li>اتصال إنترنت مستقر</li>
                        <li>متصفح حديث (Chrome, Firefox, Safari, Edge)</li>
                        <li>يمكن تحميل التطبيق من متجر Google Play أو App Store</li>
                    </ul>
                    
                    <h3>سياسة الاسترداد</h3>
                    <h4>ضمان استرداد الأموال:</h4>
                    <ul>
                        <li>ضمان استرداد كامل المبلغ خلال 14 يوماً من تاريخ الشراء</li>
                        <li>يجب ألا تكون قد شاهدت أكثر من 25% من محتوى الكورس</li>
                        <li>لا ينطبق على الكورسات المجانية أو العروض الخاصة</li>
                    </ul>
                    
                    <h4>الكورسات غير القابلة للاسترداد:</h4>
                    <ul>
                        <li>الكورسات التي تم استكمال أكثر من 25% منها</li>
                        <li>الكورسات المشتراة بخصم أكثر من 70%</li>
                        <li>الباقات والاشتراكات بعد استخدامها</li>
                        <li>الكورسات بعد مرور 14 يوماً على الشراء</li>
                    </ul>
                    
                    <h4>خطوات الاسترداد:</h4>
                    <ol>
                        <li>تقديم طلب استرداد من خلال حسابك أو التواصل مع خدمة العملاء</li>
                        <li>انتظار مراجعة طلب الاسترداد (1-2 يوم عمل)</li>
                        <li>في حالة الموافقة، سيتم استرداد المبلغ خلال 5-7 أيام عمل</li>
                    </ol>
                    
                    <h4>طريقة الاسترداد:</h4>
                    <p>سيتم استرداد المبلغ بنفس طريقة الدفع المستخدمة. في حالة الدفع عند الاستلام أو التحويل البنكي، سيتم التحويل لحسابك البنكي.</p>
                </div>',
                'en' => '<div class="shipping-returns-content">
                    <h2>Access and Refund Policy</h2>
                    
                    <h3>Course Access</h3>
                    <h4>How to Access:</h4>
                    <ul>
                        <li>After completing purchase, the course will be immediately available in your account</li>
                        <li>You can watch courses from any device (computer, mobile, tablet)</li>
                        <li>Lifetime access to the course after purchase</li>
                    </ul>
                    
                    <h4>Viewing Requirements:</h4>
                    <ul>
                        <li>Stable internet connection</li>
                        <li>Modern browser (Chrome, Firefox, Safari, Edge)</li>
                        <li>App available on Google Play and App Store</li>
                    </ul>
                    
                    <h3>Refund Policy</h3>
                    <h4>Money-Back Guarantee:</h4>
                    <ul>
                        <li>Full refund guarantee within 14 days of purchase</li>
                        <li>Must not have watched more than 25% of course content</li>
                        <li>Does not apply to free courses or special offers</li>
                    </ul>
                    
                    <h4>Non-Refundable Courses:</h4>
                    <ul>
                        <li>Courses with more than 25% completion</li>
                        <li>Courses purchased with more than 70% discount</li>
                        <li>Bundles and subscriptions after use</li>
                        <li>Courses after 14 days from purchase</li>
                    </ul>
                    
                    <h4>Refund Steps:</h4>
                    <ol>
                        <li>Submit refund request through your account or contact customer service</li>
                        <li>Wait for refund request review (1-2 business days)</li>
                        <li>If approved, refund will be processed within 5-7 business days</li>
                    </ol>
                    
                    <h4>Refund Method:</h4>
                    <p>Refund will be made using the same payment method. For cash on delivery or bank transfer payments, refund will be transferred to your bank account.</p>
                </div>',
            ],
            'privacy_policy' => [
                'ar' => '<div class="privacy-policy-content">
                    <h2>سياسة الخصوصية</h2>
                    <p><em>آخر تحديث: فبراير 2026</em></p>
                    
                    <h3>مقدمة</h3>
                    <p>نحن في منصة علّمني نلتزم بحماية خصوصيتك وبياناتك الشخصية. توضح هذه السياسة كيفية جمع واستخدام وحماية معلوماتك الشخصية عند استخدام منصتنا.</p>
                    
                    <h3>المعلومات التي نجمعها</h3>
                    <h4>معلومات الحساب:</h4>
                    <ul>
                        <li>الاسم الكامل</li>
                        <li>البريد الإلكتروني</li>
                        <li>رقم الهاتف</li>
                        <li>الدولة والمدينة</li>
                    </ul>
                    
                    <h4>معلومات التعلم:</h4>
                    <ul>
                        <li>الكورسات المشتراة والمسجل فيها</li>
                        <li>تقدمك في الكورسات ونسبة الإتمام</li>
                        <li>الشهادات الحاصل عليها</li>
                        <li>تقييماتك وتعليقاتك</li>
                    </ul>
                    
                    <h4>معلومات الدفع:</h4>
                    <ul>
                        <li>معلومات الدفع (يتم تشفيرها بشكل آمن)</li>
                        <li>سجل المشتريات</li>
                    </ul>
                    
                    <h4>معلومات التصفح:</h4>
                    <ul>
                        <li>عنوان IP</li>
                        <li>نوع المتصفح والجهاز</li>
                        <li>صفحات المنصة المزارة</li>
                        <li>مدة المشاهدة</li>
                    </ul>
                    
                    <h3>كيفية استخدام المعلومات</h3>
                    <p>نستخدم معلوماتك الشخصية للأغراض التالية:</p>
                    <ul>
                        <li>توفير الوصول للكورسات المشتراة</li>
                        <li>تتبع تقدمك التعليمي وإصدار الشهادات</li>
                        <li>تحسين تجربة التعلم وتخصيص التوصيات</li>
                        <li>التواصل معك بشأن الكورسات والتحديثات</li>
                        <li>إرسال عروض وكورسات جديدة (يمكنك إلغاء الاشتراك)</li>
                        <li>منع الاحتيال وحماية المنصة</li>
                    </ul>
                    
                    <h3>حماية معلوماتك</h3>
                    <p>نتخذ إجراءات أمنية صارمة لحماية بياناتك الشخصية:</p>
                    <ul>
                        <li>تشفير جميع المعاملات المالية باستخدام SSL</li>
                        <li>تخزين آمن للبيانات على خوادم محمية</li>
                        <li>حماية الفيديوهات من التحميل غير المصرح به</li>
                        <li>مراقبة منتظمة للأنظمة الأمنية</li>
                    </ul>
                    
                    <h3>مشاركة المعلومات</h3>
                    <p>نحن لا نبيع أو نؤجر معلوماتك الشخصية لأطراف ثالثة. قد نشارك معلوماتك مع:</p>
                    <ul>
                        <li>معالجات الدفع لإتمام المعاملات</li>
                        <li>خدمات التحليلات لتحسين المنصة</li>
                        <li>السلطات القانونية عند الضرورة القانونية</li>
                    </ul>
                    
                    <h3>ملفات تعريف الارتباط (Cookies)</h3>
                    <p>نستخدم ملفات تعريف الارتباط لتحسين تجربة التعلم وتذكر تفضيلاتك. يمكنك تعطيل ملفات تعريف الارتباط من إعدادات المتصفح.</p>
                    
                    <h3>حقوقك</h3>
                    <p>لديك الحق في:</p>
                    <ul>
                        <li>الوصول إلى بياناتك الشخصية</li>
                        <li>تصحيح البيانات غير الدقيقة</li>
                        <li>تحميل بياناتك التعليمية</li>
                        <li>حذف حسابك وبياناتك</li>
                        <li>الاعتراض على معالجة بياناتك</li>
                    </ul>
                    
                    <h3>التواصل معنا</h3>
                    <p>لأي استفسارات حول سياسة الخصوصية، يرجى التواصل معنا على:</p>
                    <p>البريد الإلكتروني: privacy@alemni.com<br>
                    الهاتف: 02-XXXXXXXX</p>
                    
                    <h3>التحديثات على السياسة</h3>
                    <p>قد نقوم بتحديث هذه السياسة من وقت لآخر. سنقوم بإعلامك بأي تغييرات جوهرية عبر البريد الإلكتروني أو إشعار على المنصة.</p>
                </div>',
                'en' => '<div class="privacy-policy-content">
                    <h2>Privacy Policy</h2>
                    <p><em>Last Updated: February 2026</em></p>
                    
                    <h3>Introduction</h3>
                    <p>At Alemni Platform, we are committed to protecting your privacy and personal data. This policy explains how we collect, use, and protect your personal information when using our platform.</p>
                    
                    <h3>Information We Collect</h3>
                    <h4>Account Information:</h4>
                    <ul>
                        <li>Full name</li>
                        <li>Email address</li>
                        <li>Phone number</li>
                        <li>Country and city</li>
                    </ul>
                    
                    <h4>Learning Information:</h4>
                    <ul>
                        <li>Purchased and enrolled courses</li>
                        <li>Your progress and completion rate</li>
                        <li>Certificates earned</li>
                        <li>Your ratings and comments</li>
                    </ul>
                    
                    <h4>Payment Information:</h4>
                    <ul>
                        <li>Payment information (securely encrypted)</li>
                        <li>Purchase history</li>
                    </ul>
                    
                    <h4>Browsing Information:</h4>
                    <ul>
                        <li>IP address</li>
                        <li>Browser and device type</li>
                        <li>Pages visited on the platform</li>
                        <li>Viewing duration</li>
                    </ul>
                    
                    <h3>How We Use Your Information</h3>
                    <p>We use your personal information for the following purposes:</p>
                    <ul>
                        <li>Provide access to purchased courses</li>
                        <li>Track your learning progress and issue certificates</li>
                        <li>Improve learning experience and personalize recommendations</li>
                        <li>Communicate with you about courses and updates</li>
                        <li>Send offers and new courses (you can unsubscribe)</li>
                        <li>Prevent fraud and protect the platform</li>
                    </ul>
                    
                    <h3>Protecting Your Information</h3>
                    <p>We take strict security measures to protect your personal data:</p>
                    <ul>
                        <li>Encrypt all financial transactions using SSL</li>
                        <li>Secure data storage on protected servers</li>
                        <li>Video protection from unauthorized downloads</li>
                        <li>Regular monitoring of security systems</li>
                    </ul>
                    
                    <h3>Information Sharing</h3>
                    <p>We do not sell or rent your personal information to third parties. We may share your information with:</p>
                    <ul>
                        <li>Payment processors to complete transactions</li>
                        <li>Analytics services to improve the platform</li>
                        <li>Legal authorities when legally required</li>
                    </ul>
                    
                    <h3>Cookies</h3>
                    <p>We use cookies to improve learning experience and remember your preferences. You can disable cookies from browser settings.</p>
                    
                    <h3>Your Rights</h3>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access your personal data</li>
                        <li>Correct inaccurate data</li>
                        <li>Download your learning data</li>
                        <li>Delete your account and data</li>
                        <li>Object to data processing</li>
                    </ul>
                    
                    <h3>Contact Us</h3>
                    <p>For any inquiries about the privacy policy, please contact us at:</p>
                    <p>Email: privacy@alemni.com<br>
                    Phone: 02-XXXXXXXX</p>
                    
                    <h3>Policy Updates</h3>
                    <p>We may update this policy from time to time. We will notify you of any material changes via email or platform notification.</p>
                </div>',
            ],
            'terms_and_conditions' => [
                'ar' => '<div class="terms-conditions-content">
                    <h2>الشروط والأحكام</h2>
                    <p><em>آخر تحديث: فبراير 2026</em></p>
                    
                    <h3>مقدمة</h3>
                    <p>مرحباً بك في منصة علّمني. باستخدامك لمنصتنا التعليمية، فإنك توافق على الالتزام بهذه الشروط والأحكام. يرجى قراءتها بعناية قبل استخدام خدماتنا.</p>
                    
                    <h3>استخدام المنصة</h3>
                    <h4>شروط الاستخدام:</h4>
                    <ul>
                        <li>يجب أن تكون بعمر 16 عاماً أو أكثر لاستخدام المنصة</li>
                        <li>يجب عليك تقديم معلومات دقيقة وكاملة عند التسجيل</li>
                        <li>أنت مسؤول عن الحفاظ على سرية حسابك وكلمة المرور</li>
                        <li>لا يجوز مشاركة حسابك أو الكورسات مع آخرين</li>
                        <li>لا يجوز تحميل أو نسخ أو توزيع محتوى الكورسات</li>
                        <li>نحتفظ بالحق في تعليق أو إنهاء حسابك في حالة انتهاك هذه الشروط</li>
                    </ul>
                    
                    <h3>الكورسات والمحتوى</h3>
                    <h4>حقوق الوصول:</h4>
                    <ul>
                        <li>بعد الشراء، تحصل على حق الوصول للكورس مدى الحياة</li>
                        <li>الوصول شخصي ولا يجوز نقله لشخص آخر</li>
                        <li>يمكن مشاهدة الكورس من أي جهاز مرتبط بحسابك</li>
                    </ul>
                    
                    <h4>الملكية الفكرية:</h4>
                    <p>جميع الكورسات والمحتوى محمية بموجب قوانين حقوق النشر. لا يجوز:</p>
                    <ul>
                        <li>تحميل الفيديوهات أو المواد التعليمية</li>
                        <li>تسجيل الشاشة أثناء المشاهدة</li>
                        <li>مشاركة المحتوى على أي منصة أخرى</li>
                        <li>إعادة بيع أو توزيع المحتوى</li>
                    </ul>
                    
                    <h3>الدفع والأسعار</h3>
                    <h4>طرق الدفع:</h4>
                    <ul>
                        <li>بطاقات الائتمان والخصم (Visa, Mastercard)</li>
                        <li>فوري (Fawry)</li>
                        <li>فودافون كاش وأورانج كاش</li>
                        <li>التحويل البنكي</li>
                    </ul>
                    
                    <h4>الأسعار:</h4>
                    <p>جميع الأسعار معروضة بالجنيه المصري. نحتفظ بالحق في تعديل الأسعار دون إشعار مسبق، لكن السعر المدفوع عند الشراء هو السعر النهائي.</p>
                    
                    <h3>الشهادات</h3>
                    <ul>
                        <li>تحصل على شهادة إتمام عند إنهاء الكورس بنسبة 100%</li>
                        <li>الشهادة رقمية ويمكن مشاركتها على LinkedIn</li>
                        <li>الشهادة تحمل اسمك ورقم تحقق فريد</li>
                    </ul>
                    
                    <h3>سياسة الاسترداد</h3>
                    <ul>
                        <li>ضمان استرداد الأموال خلال 14 يوماً</li>
                        <li>يجب ألا تكون قد شاهدت أكثر من 25% من الكورس</li>
                        <li>بعض الكورسات والعروض غير قابلة للاسترداد</li>
                    </ul>
                    
                    <h3>حدود المسؤولية</h3>
                    <p>لن نكون مسؤولين عن:</p>
                    <ul>
                        <li>أي أضرار ناتجة عن استخدام أو عدم القدرة على استخدام المنصة</li>
                        <li>أي أخطاء أو سهو في المحتوى التعليمي</li>
                        <li>أي انقطاع في الخدمة بسبب الصيانة أو ظروف خارجة عن سيطرتنا</li>
                        <li>نتائج تطبيق ما تعلمته من الكورسات</li>
                    </ul>
                    
                    <h3>القانون الحاكم</h3>
                    <p>تخضع هذه الشروط والأحكام لقوانين جمهورية مصر العربية. أي نزاع ينشأ عن هذه الشروط سيتم حله في المحاكم المصرية المختصة.</p>
                    
                    <h3>التغييرات على الشروط</h3>
                    <p>نحتفظ بالحق في تعديل هذه الشروط والأحكام في أي وقت. سيتم إشعارك بأي تغييرات جوهرية عبر البريد الإلكتروني أو إشعار على المنصة.</p>
                    
                    <h3>التواصل معنا</h3>
                    <p>لأي استفسارات حول الشروط والأحكام، يرجى التواصل معنا على:</p>
                    <p>البريد الإلكتروني: info@alemni.com<br>
                    الهاتف: 02-XXXXXXXX<br>
                    العنوان: القاهرة، جمهورية مصر العربية</p>
                </div>',
                'en' => '<div class="terms-conditions-content">
                    <h2>Terms and Conditions</h2>
                    <p><em>Last Updated: February 2026</em></p>
                    
                    <h3>Introduction</h3>
                    <p>Welcome to Alemni Platform. By using our educational platform, you agree to comply with these terms and conditions. Please read them carefully before using our services.</p>
                    
                    <h3>Platform Usage</h3>
                    <h4>Terms of Use:</h4>
                    <ul>
                        <li>You must be 16 years or older to use the platform</li>
                        <li>You must provide accurate and complete information when registering</li>
                        <li>You are responsible for maintaining the confidentiality of your account and password</li>
                        <li>You may not share your account or courses with others</li>
                        <li>You may not download, copy, or distribute course content</li>
                        <li>We reserve the right to suspend or terminate your account in case of violation</li>
                    </ul>
                    
                    <h3>Courses and Content</h3>
                    <h4>Access Rights:</h4>
                    <ul>
                        <li>After purchase, you get lifetime access to the course</li>
                        <li>Access is personal and cannot be transferred to another person</li>
                        <li>You can watch the course from any device linked to your account</li>
                    </ul>
                    
                    <h4>Intellectual Property:</h4>
                    <p>All courses and content are protected by copyright laws. You may not:</p>
                    <ul>
                        <li>Download videos or educational materials</li>
                        <li>Screen record while watching</li>
                        <li>Share content on any other platform</li>
                        <li>Resell or distribute content</li>
                    </ul>
                    
                    <h3>Payment and Pricing</h3>
                    <h4>Payment Methods:</h4>
                    <ul>
                        <li>Credit and debit cards (Visa, Mastercard)</li>
                        <li>Fawry</li>
                        <li>Vodafone Cash and Orange Cash</li>
                        <li>Bank transfer</li>
                    </ul>
                    
                    <h4>Pricing:</h4>
                    <p>All prices are displayed in Egyptian Pounds. We reserve the right to modify prices without prior notice, but the price paid at purchase is final.</p>
                    
                    <h3>Certificates</h3>
                    <ul>
                        <li>You receive a completion certificate upon finishing the course 100%</li>
                        <li>Certificate is digital and can be shared on LinkedIn</li>
                        <li>Certificate bears your name and unique verification number</li>
                    </ul>
                    
                    <h3>Refund Policy</h3>
                    <ul>
                        <li>14-day money-back guarantee</li>
                        <li>Must not have watched more than 25% of the course</li>
                        <li>Some courses and offers are non-refundable</li>
                    </ul>
                    
                    <h3>Limitation of Liability</h3>
                    <p>We will not be liable for:</p>
                    <ul>
                        <li>Any damages resulting from use or inability to use the platform</li>
                        <li>Any errors or omissions in educational content</li>
                        <li>Any service interruption due to maintenance or circumstances beyond our control</li>
                        <li>Results of applying what you learned from courses</li>
                    </ul>
                    
                    <h3>Governing Law</h3>
                    <p>These terms and conditions are governed by the laws of the Arab Republic of Egypt. Any dispute arising from these terms will be resolved in the competent Egyptian courts.</p>
                    
                    <h3>Changes to Terms</h3>
                    <p>We reserve the right to modify these terms and conditions at any time. You will be notified of any material changes via email or platform notification.</p>
                    
                    <h3>Contact Us</h3>
                    <p>For any inquiries about the terms and conditions, please contact us at:</p>
                    <p>Email: info@alemni.com<br>
                    Phone: 02-XXXXXXXX<br>
                    Address: Cairo, Arab Republic of Egypt</p>
                </div>',
            ],
            'refund_policy' => [
                'ar' => '<div class="refund-policy-content">
                    <h2>سياسة الاسترداد</h2>
                    <p><em>آخر تحديث: فبراير 2026</em></p>
                    
                    <h3>مقدمة</h3>
                    <p>نحن في متجر رفية نسعى لتوفير أفضل تجربة تسوق لعملائنا. إذا لم تكن راضياً تماماً عن مشترياتك، يمكنك إرجاع المنتجات واسترداد قيمتها وفقاً للشروط الموضحة أدناه.</p>
                    
                    <h3>مدة الاسترجاع</h3>
                    <ul>
                        <li>14 يوماً من تاريخ استلام المنتج للمنتجات الصغيرة والإكسسوارات</li>
                        <li>7 أيام من تاريخ استلام المنتج لقطع الأثاث</li>
                        <li>يجب تقديم طلب الإرجاع خلال المدة المحددة</li>
                    </ul>
                    
                    <h3>شروط الاسترجاع</h3>
                    <h4>المنتجات القابلة للإرجاع:</h4>
                    <ul>
                        <li>المنتج في حالته الأصلية دون استخدام أو تلف</li>
                        <li>التغليف الأصلي سليم وكامل</li>
                        <li>جميع الملحقات والأجزاء موجودة</li>
                        <li>إرفاق فاتورة الشراء الأصلية</li>
                        <li>عدم وجود علامات استخدام أو خدوش</li>
                    </ul>
                    
                    <h4>المنتجات غير القابلة للإرجاع:</h4>
                    <ul>
                        <li>المنتجات المخصصة حسب الطلب أو المصنوعة خصيصاً للعميل</li>
                        <li>المنتجات المستخدمة أو التي تظهر عليها علامات الاستخدام</li>
                        <li>المنتجات المعيبة بسبب سوء الاستخدام أو الإهمال</li>
                        <li>منتجات التخفيضات النهائية (Final Sale) والعروض الخاصة</li>
                        <li>المنتجات التي تم فتح تغليفها الأصلي (للمنتجات الصحية)</li>
                    </ul>
                    
                    <h3>خطوات الاسترجاع</h3>
                    <ol>
                        <li><strong>تقديم الطلب:</strong> قم بتسجيل الدخول إلى حسابك وتقديم طلب إرجاع، أو تواصل مع خدمة العملاء</li>
                        <li><strong>المراجعة:</strong> سيتم مراجعة طلبك خلال 1-2 يوم عمل</li>
                        <li><strong>الموافقة:</strong> عند الموافقة، سيتم إرسال تعليمات الإرجاع إليك</li>
                        <li><strong>التغليف:</strong> قم بتغليف المنتج بشكل آمن في التغليف الأصلي</li>
                        <li><strong>الشحن:</strong> سلم المنتج لمندوب الشحن أو قم بشحنه إلى العنوان المحدد</li>
                        <li><strong>الفحص:</strong> سيتم فحص المنتج عند وصوله للتأكد من مطابقته للشروط</li>
                        <li><strong>الاسترداد:</strong> سيتم استرداد المبلغ خلال 7-10 أيام عمل بعد الموافقة</li>
                    </ol>
                    
                    <h3>طرق الاسترداد</h3>
                    <ul>
                        <li><strong>الدفع الإلكتروني:</strong> سيتم الاسترداد إلى نفس طريقة الدفع المستخدمة</li>
                        <li><strong>الدفع عند الاستلام:</strong> سيتم الاسترداد عن طريق تحويل بنكي (يرجى تقديم بيانات الحساب)</li>
                        <li><strong>رصيد المتجر:</strong> يمكنك اختيار الحصول على رصيد في المتجر لاستخدامه في مشتريات مستقبلية</li>
                    </ul>
                    
                    <h3>رسوم الإرجاع</h3>
                    <ul>
                        <li><strong>عيب في المنتج:</strong> نتحمل نحن كامل تكاليف الإرجاع والشحن</li>
                        <li><strong>تغيير الرأي:</strong> يتحمل العميل رسوم إعادة الشحن (50-100 جنية حسب حجم المنتج)</li>
                        <li><strong>طلب خاطئ:</strong> إذا تم إرسال منتج خاطئ من طرفنا، نتحمل كامل التكاليف</li>
                    </ul>
                    
                    <h3>الاستبدال</h3>
                    <p>يمكنك استبدال المنتج بمنتج آخر من نفس القيمة أو أعلى (مع دفع الفرق) خلال نفس مدة الإرجاع. شروط الاستبدال هي نفسها شروط الإرجاع.</p>

                    <h3>حالات خاصة</h3>
                    <h4>المنتجات المعيبة:</h4>
                    <p>إذا استلمت منتجاً معيباً أو تالفاً، يرجى التواصل معنا فوراً خلال 48 ساعة من الاستلام. سنقوم باستبدال المنتج أو استرداد كامل المبلغ بالإضافة إلى تحمل كافة تكاليف الشحن.</p>
                    
                    <h4>الطلبات الخاصة:</h4>
                    <p>المنتجات المصنوعة حسب الطلب أو المخصصة لا يمكن إرجاعها إلا في حالة وجود عيب صناعي.</p>
                    
                    <h3>التواصل</h3>
                    <p>لأي استفسارات حول سياسة الاسترجاع:</p>
                    <p>البريد الإلكتروني: returns@template1.com<br>
                    الهاتف: 920000000<br>
                    ساعات العمل: من السبت إلى الخميس، 9 صباحاً - 6 مساءً</p>
                </div>',
                'en' => '<div class="refund-policy-content">
                    <h2>Refund and Return Policy</h2>
                    <p><em>Last Updated: December 2025</em></p>
                    
                    <h3>Introduction</h3>
                    <p>At Template1 Store, we strive to provide the best shopping experience for our customers. If you are not completely satisfied with your purchase, you can return products and receive a refund according to the conditions outlined below.</p>
                    
                    <h3>Return Period</h3>
                    <ul>
                        <li>14 days from receipt date for small products and accessories</li>
                        <li>7 days from receipt date for furniture items</li>
                        <li>Return request must be submitted within the specified period</li>
                    </ul>
                    
                    <h3>Return Conditions</h3>
                    <h4>Returnable Products:</h4>
                    <ul>
                        <li>Product in original condition without use or damage</li>
                        <li>Original packaging intact and complete</li>
                        <li>All accessories and parts present</li>
                        <li>Original purchase invoice attached</li>
                        <li>No signs of use or scratches</li>
                    </ul>
                    
                    <h4>Non-Returnable Products:</h4>
                    <ul>
                        <li>Custom-made or specially manufactured products</li>
                        <li>Used products or those showing signs of use</li>
                        <li>Products damaged due to misuse or negligence</li>
                        <li>Final Sale items and special offers</li>
                        <li>Products with opened original packaging (for hygiene products)</li>
                    </ul>
                    
                    <h3>Return Steps</h3>
                    <ol>
                        <li><strong>Submit Request:</strong> Log in to your account and submit a return request, or contact customer service</li>
                        <li><strong>Review:</strong> Your request will be reviewed within 1-2 business days</li>
                        <li><strong>Approval:</strong> Upon approval, return instructions will be sent to you</li>
                        <li><strong>Packaging:</strong> Pack the product securely in original packaging</li>
                        <li><strong>Shipping:</strong> Hand over the product to shipping representative or ship to specified address</li>
                        <li><strong>Inspection:</strong> Product will be inspected upon arrival to ensure compliance with conditions</li>
                        <li><strong>Refund:</strong> Amount will be refunded within 7-10 business days after approval</li>
                    </ol>
                    
                    <h3>Refund Methods</h3>
                    <ul>
                        <li><strong>Electronic Payment:</strong> Refund to same payment method used</li>
                        <li><strong>Cash on Delivery:</strong> Refund via bank transfer (please provide account details)</li>
                        <li><strong>Store Credit:</strong> You can choose to receive store credit for future purchases</li>
                    </ul>
                    
                    <h3>Return Fees</h3>
                    <ul>
                        <li><strong>Product Defect:</strong> We bear all return and shipping costs</li>
                        <li><strong>Change of Mind:</strong> Customer bears return shipping fees (50-100 SAR depending on product size)</li>
                        <li><strong>Wrong Order:</strong> If wrong product was sent by us, we bear all costs</li>
                    </ul>
                    
                    <h3>Exchange</h3>
                    <p>You can exchange the product for another of same or higher value (paying the difference) within the same return period. Exchange conditions are the same as return conditions.</p>

                    <h3>Special Cases</h3>
                    <h4>Defective Products:</h4>
                    <p>If you receive a defective or damaged product, please contact us immediately within 48 hours of receipt. We will replace the product or refund the full amount plus bear all shipping costs.</p>
                    
                    <h4>Special Orders:</h4>
                    <p>Custom-made or personalized products cannot be returned except in case of manufacturing defect.</p>
                    
                    <h3>Contact</h3>
                    <p>For any inquiries about the return policy:</p>
                    <p>Email: returns@template1.com<br>
                    Phone: 920000000<br>
                    Working Hours: Saturday to Thursday, 9 AM - 6 PM</p>
                </div>',
            ],
            'shipping_policy' => [
                'ar' => '<div class="shipping-policy-content">
                    <h2>سياسة الشحن والتوصيل</h2>
                    <p><em>آخر تحديث: ديسمبر 2025</em></p>
                    
                    <h3>مناطق التوصيل</h3>
                    <p>نوفر خدمة التوصيل إلى جميع مناطق جمهورية مصر العربية:</p>
                    <ul>
                        <li><strong>المدن الرئيسية:</strong> القاهرة، الجيزة، الإسكندرية، الدمنهور، الإسكندرية</li>
                        <li><strong>المدن الأخرى:</strong> جميع مدن المملكة</li>
                        <li><strong>المناطق النائية:</strong> قد تستغرق وقتاً إضافياً وتخضع لرسوم إضافية</li>
                    </ul>
                    
                    <h3>مدة التوصيل</h3>
                    <h4>المنتجات الصغيرة والإكسسوارات:</h4>
                    <ul>
                        <li>المدن الرئيسية: 2-3 أيام عمل</li>
                        <li>المدن الأخرى: 3-5 أيام عمل</li>
                        <li>المناطق النائية: 5-7 أيام عمل</li>
                    </ul>
                    
                    <h4>قطع الأثاث:</h4>
                    <ul>
                        <li>المدن الرئيسية: 5-7 أيام عمل</li>
                        <li>المدن الأخرى: 7-10 أيام عمل</li>
                        <li>المناطق النائية: 10-14 يوم عمل</li>
                    </ul>
                    
                    <h4>الطلبات الخاصة والمخصصة:</h4>
                    <ul>
                        <li>10-14 يوم عمل للتصنيع</li>
                        <li>إضافة 5-7 أيام للتوصيل</li>
                    </ul>
                    
                    <h3>رسوم الشحن</h3>
                    <table style="width:100%; border-collapse: collapse; margin: 20px 0;">
                        <tr style="background-color: #f8f9fa;">
                            <th style="padding: 10px; border: 1px solid #dee2e6;">قيمة الطلب</th>
                            <th style="padding: 10px; border: 1px solid #dee2e6;">رسوم الشحن</th>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">أكثر من 500 جنية</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6; color: #28a745; font-weight: bold;">مجاني</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">300 - 500 جنية</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">30 جنية</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">أقل من 300 جنية</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">50 جنية</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">قطع الأثاث الكبيرة</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">100-200 جنية (حسب الحجم)</td>
                        </tr>
                    </table>
                    
                    <p><strong>ملاحظة:</strong> قد تطبق رسوم إضافية للمناطق النائية (50-100 جنية).</p>
                    
                    <h3>خدمة التركيب</h3>
                    <p>نوفر خدمة تركيب احترافية لقطع الأثاث:</p>
                    <ul>
                        <li><strong>الأثاث الصغير:</strong> 50-100 جنية</li>
                        <li><strong>الأثاث المتوسط:</strong> 100-200 جنية</li>
                        <li><strong>الأثاث الكبير:</strong> 200-400 جنية</li>
                        <li><strong>المطابخ والغرف الكاملة:</strong> حسب التقدير</li>
                    </ul>
                    
                    <h3>تتبع الشحنة</h3>
                    <p>يمكنك تتبع شحنتك بسهولة:</p>
                    <ol>
                        <li>سجل الدخول إلى حسابك</li>
                        <li>اذهب إلى "طلباتي"</li>
                        <li>اضغط على رقم الطلب لعرض التفاصيل</li>
                        <li>استخدم رقم التتبع لمعرفة موقع شحنتك</li>
                    </ol>
                    
                    <h3>التوصيل</h3>
                    <h4>قبل التوصيل:</h4>
                    <ul>
                        <li>سيتصل بك مندوب التوصيل قبل 24 ساعة لتحديد موعد مناسب</li>
                        <li>يمكنك إعادة جدولة الموعد حسب الحاجة</li>
                        <li>تأكد من وجود شخص بالغ لاستلام الطلب</li>
                    </ul>
                    
                    <h4>عند التوصيل:</h4>
                    <ul>
                        <li>تحقق من الطرد قبل التوقيع على الاستلام</li>
                        <li>تأكد من عدم وجود أضرار ظاهرية</li>
                        <li>تأكد من مطابقة المنتجات للفاتورة</li>
                        <li>في حالة وجود مشكلة، لا توقع على الاستلام وتواصل معنا فوراً</li>
                    </ul>
                    
                    <h3>حالات خاصة</h3>
                    <h4>عدم التمكن من التوصيل:</h4>
                    <p>في حالة عدم وجود أحد لاستلام الطلب:</p>
                    <ul>
                        <li>سيحاول المندوب الاتصال بك</li>
                        <li>سيتم ترك إشعار بالزيارة</li>
                        <li>يمكنك تحديد موعد جديد خلال 3 أيام</li>
                        <li>بعد 3 محاولات فاشلة، سيتم إعادة الطلب للمستودع</li>
                    </ul>
                    
                    <h4>الطلبات العاجلة:</h4>
                    <p>نوفر خدمة التوصيل السريع للطلبات العاجلة:</p>
                    <ul>
                        <li>التوصيل خلال 24 ساعة في الرياض وجدة</li>
                        <li>رسوم إضافية: 100 جنية</li>
                        <li>متاح فقط للمنتجات الصغيرة والمتوفرة في المخزون</li>
                    </ul>
                    
                    <h3>الشحن الدولي</h3>
                    <p>حالياً نوفر الشحن داخل المملكة فقط. نعمل على توفير خدمة الشحن الدولي قريباً.</p>
                    
                    <h3>التواصل</h3>
                    <p>لأي استفسارات حول الشحن والتوصيل:</p>
                    <p>البريد الإلكتروني: shipping@template1.com<br>
                    الهاتف: 920000000<br>
                    واتساب: +966 50 000 0000<br>
                    ساعات العمل: من السبت إلى الخميس، 9 صباحاً - 6 مساءً</p>
                </div>',
                'en' => '<div class="shipping-policy-content">
                    <h2>Shipping and Delivery Policy</h2>
                    <p><em>Last Updated: December 2025</em></p>
                    
                    <h3>Delivery Areas</h3>
                    <p>We provide delivery service to all regions of Saudi Arabia:</p>
                    <ul>
                        <li><strong>Major Cities:</strong> Riyadh, Jeddah, Dammam, Makkah, Madinah</li>
                        <li><strong>Other Cities:</strong> All cities in the Kingdom</li>
                        <li><strong>Remote Areas:</strong> May take additional time and subject to extra fees</li>
                    </ul>
                    
                    <h3>Delivery Time</h3>
                    <h4>Small Products and Accessories:</h4>
                    <ul>
                        <li>Major Cities: 2-3 business days</li>
                        <li>Other Cities: 3-5 business days</li>
                        <li>Remote Areas: 5-7 business days</li>
                    </ul>
                    
                    <h4>Furniture Items:</h4>
                    <ul>
                        <li>Major Cities: 5-7 business days</li>
                        <li>Other Cities: 7-10 business days</li>
                        <li>Remote Areas: 10-14 business days</li>
                    </ul>
                    
                    <h4>Special and Custom Orders:</h4>
                    <ul>
                        <li>10-14 business days for manufacturing</li>
                        <li>Additional 5-7 days for delivery</li>
                    </ul>
                    
                    <h3>Shipping Fees</h3>
                    <table style="width:100%; border-collapse: collapse; margin: 20px 0;">
                        <tr style="background-color: #f8f9fa;">
                            <th style="padding: 10px; border: 1px solid #dee2e6;">Order Value</th>
                            <th style="padding: 10px; border: 1px solid #dee2e6;">Shipping Fee</th>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">Over 500 SAR</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6; color: #28a745; font-weight: bold;">Free</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">300 - 500 SAR</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">30 SAR</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">Less than 300 SAR</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">50 SAR</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">Large Furniture</td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">100-200 SAR (size dependent)</td>
                        </tr>
                    </table>
                    
                    <p><strong>Note:</strong> Additional fees may apply for remote areas (50-100 SAR).</p>
                    
                    <h3>Installation Service</h3>
                    <p>We provide professional installation service for furniture:</p>
                    <ul>
                        <li><strong>Small Furniture:</strong> 50-100 SAR</li>
                        <li><strong>Medium Furniture:</strong> 100-200 SAR</li>
                        <li><strong>Large Furniture:</strong> 200-400 SAR</li>
                        <li><strong>Kitchens and Complete Rooms:</strong> Upon estimate</li>
                    </ul>
                    
                    <h3>Track Your Shipment</h3>
                    <p>You can easily track your shipment:</p>
                    <ol>
                        <li>Log in to your account</li>
                        <li>Go to "My Orders"</li>
                        <li>Click on order number to view details</li>
                        <li>Use tracking number to know your shipment location</li>
                    </ol>
                    
                    <h3>Delivery</h3>
                    <h4>Before Delivery:</h4>
                    <ul>
                        <li>Delivery representative will call you 24 hours before to set a suitable time</li>
                        <li>You can reschedule as needed</li>
                        <li>Ensure an adult is present to receive the order</li>
                    </ul>
                    
                    <h4>Upon Delivery:</h4>
                    <ul>
                        <li>Check the package before signing for receipt</li>
                        <li>Ensure no visible damage</li>
                        <li>Verify products match the invoice</li>
                        <li>If there is a problem, do not sign and contact us immediately</li>
                    </ul>
                    
                    <h3>Special Cases</h3>
                    <h4>Failed Delivery:</h4>
                    <p>If no one is available to receive the order:</p>
                    <ul>
                        <li>Representative will try to contact you</li>
                        <li>A visit notice will be left</li>
                        <li>You can set a new date within 3 days</li>
                        <li>After 3 failed attempts, order will be returned to warehouse</li>
                    </ul>
                    
                    <h4>Urgent Orders:</h4>
                    <p>We provide express delivery service for urgent orders:</p>
                    <ul>
                        <li>Delivery within 24 hours in Riyadh and Jeddah</li>
                        <li>Additional fee: 100 SAR</li>
                        <li>Available only for small products in stock</li>
                    </ul>
                    
                    <h3>International Shipping</h3>
                    <p>Currently we provide shipping within Saudi Arabia only. We are working on providing international shipping soon.</p>
                    
                    <h3>Contact</h3>
                    <p>For any inquiries about shipping and delivery:</p>
                    <p>Email: shipping@template1.com<br>
                    Phone: 920000000<br>
                    WhatsApp: +966 50 000 0000<br>
                    Working Hours: Saturday to Thursday, 9 AM - 6 PM</p>
                </div>',
            ],
            'address' => [
                'ar' => 'القاهرة، جمهورية مصر العربية، مدينة نصر، شارع عباس العقاد',
                'en' => 'Cairo, Arab Republic of Egypt, Nasr City, Abbas El-Akkad Street',
            ],
            'phone' => '+20 2 XXXX XXXX',
            'email' => 'info@alemni.com',
        ]);
        $site->addMedia(base_path('public/logo.svg'))->preservingOriginal()->toMediaCollection('logo_white', 'public');
        $site->addMedia(base_path('public/logo.svg'))->preservingOriginal()->toMediaCollection('logo_black', 'public');
        $site->addMedia(base_path('public/favicon.svg'))->preservingOriginal()->toMediaCollection('favicon', 'public');
    }
}
