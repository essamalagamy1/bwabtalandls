<x-menu activate-by-route active-bg-color="font-black text-primary bg-primary/10 dark:bg-primary/20" class="menu-vertical">
	{{-- الرئيسية --}}
	<x-menu-item  title="{{ __('lang.home') }}" icon-classes="text-primary" icon="o-home" link="{{ route('dashboard') }}" />

	<x-menu-separator />

	{{-- منطقة الطالب --}}
	@role('student')
		<x-menu-title title="المنطقة الخاصة" />
		<x-menu-item title="امتحاناتي" icon-classes="text-primary" icon="o-document-text" link="{{ route('student.exams') }}" />
		<x-menu-item title="تدريباتي" icon-classes="text-primary" icon="o-play-circle" link="{{ route('student.trainings') }}" />
		<div class="pwa-install-container hidden">
			<x-menu-item title="تثبيت التطبيق" class="pwa-install-button text-indigo-600 dark:text-indigo-400 font-bold" icon-classes="text-indigo-600 dark:text-indigo-400" icon="o-arrow-down-tray" link="javascript:void(0)" />
		</div>
		<x-menu-separator />
	@endrole

	{{-- إدارة المستخدمين --}}
	@canany(['show_student', 'show_admin', 'show_role'])
		<x-menu-title title="{{ __('lang.users_management') }}" />

		@can('show_student')
			<x-menu-item  title="{{ __('lang.students') }}" icon-classes="text-primary" icon="o-users" link="{{ route('students') }}" />
		@endcan

		@can('show_admin')
			<x-menu-item  title="{{ __('lang.admins') }}" icon-classes="text-primary" icon="o-user-circle" link="{{ route('admins') }}" />
		@endcan

		@can('show_role')
			<x-menu-item  title="{{ __('lang.roles') }}" icon-classes="text-primary" icon="o-shield-check" link="{{ route('roles') }}" />
		@endcan

		<x-menu-separator />
	@endcanany

	{{-- المحتوى الأكاديمي --}}
	@canany(['show_stage', 'show_grade', 'show_semester', 'show_week', 'show_training', 'show_exam', 'show_question'])
		<x-menu-title title="{{ __('lang.academic_content') }}" />

		@can('show_stage')
			<x-menu-item  title="{{ __('lang.stages') }}" icon-classes="text-primary" icon="o-academic-cap" link="{{ route('stages') }}" />
		@endcan

		@can('show_grade')
			<x-menu-item  title="{{ __('lang.grades') }}" icon-classes="text-primary" icon="o-rectangle-group" link="{{ route('grades') }}" />
		@endcan

		@can('show_semester')
			<x-menu-item  title="{{ __('lang.semesters') }}" icon-classes="text-primary" icon="o-calendar" link="{{ route('semesters') }}" />
		@endcan

		@can('show_week')
			<x-menu-item  title="{{ __('lang.weeks') }}" icon-classes="text-primary" icon="o-calendar-days" link="{{ route('weeks') }}" />
		@endcan

		@can('show_training')
			<x-menu-item  title="{{ __('lang.trainings') }}" icon-classes="text-primary" icon="o-play-circle" link="{{ route('trainings') }}" />
		@endcan

		@can('show_exam')
			<x-menu-item  title="{{ __('lang.exams') }}" icon-classes="text-primary" icon="o-document-text" link="{{ route('exams') }}" />
		@endcan

		{{-- @can('show_question')
			<x-menu-item  title="{{ __('lang.questions') }}" icon-classes="text-primary" icon="o-question-mark-circle" link="{{ route('questions') }}" />
		@endcan --}}

		<x-menu-separator />
	@endcanany

	{{-- التقارير --}}
	@canany(['show_student_report', 'show_exam_report'])
		<x-menu-title title="{{ __('lang.reports_mng') }}" />

		@can('show_student_report')
			<x-menu-item  title="{{ __('lang.student_reports') }}" icon-classes="text-primary" icon="o-chart-pie" link="{{ route('reports.students') }}" />
		@endcan

		@can('show_exam_report')
			<x-menu-item  title="{{ __('lang.exam_reports') }}" icon-classes="text-primary" icon="o-chart-bar" link="{{ route('reports.exams') }}" />
		@endcan
		<x-menu-separator />
	@endcanany

	{{-- إعدادات النظام --}}
	@can('show_site_setting')
		<x-menu-title title="{{ __('lang.system_settings') }}" />
		<x-menu-item  title="{{ __('lang.settings') }}" icon-classes="text-primary" icon="o-cog-6-tooth" link="{{ route('site-settings') }}" />
	@endcan

	{{-- <x-menu-separator /> --}}

	{{-- PWA & Notifications --}}
	<div class="pwa-install-container hidden">
		<x-menu-item title="تثبيت التطبيق" icon-classes="text-indigo-600 dark:text-indigo-400" class="pwa-install-button text-indigo-600 dark:text-indigo-400 font-bold" icon="o-arrow-down-tray" link="javascript:void(0)" />
	</div>
</x-menu>
