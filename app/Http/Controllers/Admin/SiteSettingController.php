<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    private array $groups = ['general', 'content', 'contact', 'social', 'founder', 'about', 'seo', 'checkout', 'whatsapp'];

    public function index(Request $request)
    {
        $activeGroup = in_array($request->tab, $this->groups) ? $request->tab : 'general';
        $settings    = SiteSetting::getGroup($activeGroup);
        $groups      = $this->groups;

        return view('admin.settings.index', compact('settings', 'activeGroup', 'groups'));
    }

    public function update(Request $request)
    {
        $group  = $request->input('group', 'general');
        $fields = $request->except(['_token', '_method', 'group']);

        foreach ($fields as $key => $value) {
            if ($request->hasFile($key)) {
                $path  = $request->file($key)->store('settings', 'public');
                SiteSetting::updateOrCreate(['key' => $key], ['value' => $path, 'group' => $group]);
            } else {
                SiteSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '', 'group' => $group]);
            }
        }

        // Bust all setting caches for updated group
        SiteSetting::where('group', $group)->pluck('key')->each(function ($key) {
            Cache::forget("site_setting_{$key}");
        });

        return redirect()->route('admin.settings.index', ['tab' => $group])
            ->with('success', 'Settings saved.');
    }
}
