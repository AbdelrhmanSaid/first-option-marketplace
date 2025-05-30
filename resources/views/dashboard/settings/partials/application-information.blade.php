<div class="mb-3">
    <x-translatable component="input" type="text" name="app_name" :title="__('App name')" :value="setting('app_name')"
        validation="required" />
</div>

<div class="mb-3">
    <x-checkboxes :title="__('Website Languages')" :options="config('app.locales')" :inline="true" name="website_locales[]" :value="setting('website_locales', [])"
        validation="required|min:1" />
</div>

<div class="mb-3">
    <x-checkboxes :title="__('Dashboard Languages')" :options="config('app.locales')" :inline="true" name="dashboard_locales[]" :value="setting('dashboard_locales', [])"
        validation="required|min:1" />
</div>

<div class="mb-3">
    <x-toggle name="service_worker_enabled" :title="__('Service Worker')" :value="setting('service_worker_enabled')" />
</div>
