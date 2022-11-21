<?php

namespace Modules\Settings\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Base\Services\Components\Base\Alert;
use Modules\Base\Services\Components\Base\Menu;
use Modules\Base\Services\Components\Base\Render;
use Modules\Base\Services\Components\Base\Router;
use Modules\Base\Services\Resource\Page;

class Setting extends Page
{
    public ?bool $export = false;
    public ?bool $import = false;

    public function beforeLoad(Request $request, $rows): array
    {
        return $rows;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        /*
         * Check if the user has permission to view the resource
         */
        $this->loadRoles();
        /*
         * Check if the user has permission or redirect 403
         */
        if ($this->checkRoles('canView') && !$this->isAPI($request)) {
            return $this->checkRoles('canView');
        }

        /*
         * Get Setting Class
         */
        $settings = new $this->setting();

        /*
         * Check if request come from API
         */
        if($this->isAPI($request)) {
            return response()->json([
                "success" => true,
                "message" => __('Settings Loaded Success'),
                "data" => $settings->toArray()
            ]);
        }

        /*
         * Get rows to set Media
         */
        $rows = $this->rows();
        foreach($rows as $i=> $row){
            if( $row->vue === 'ViltMedia.vue'){
                $row->default = url($settings->{$row->name});
            }
            else {
                $row->default = $settings->{$row->name};
            }
        }

        /*
         * Return Render of the setting page
         */
        return Render::make('Settings')->module('Settings')->data([
            "rows" => $this->beforeLoad($request, $rows),
            "roles" => [
                "view" => $this->canView,
                "viewAny" => $this->canViewAny,
                "edit" => $this->canEdit,
                "create" => $this->canCreate,
                "delete" => $this->canDelete,
                "deleteAny" => $this->canDeleteAny,
            ],
            "render" => [
                "components" => $this->components(),
                "lang" => $this->loadTranslations(),
            ],
            "list" => [
                "url" => "admin.settings.".$this->table
            ]
        ])->render();
    }

    public function validation(Request $request): array
    {
        $validation = [];

        /*
         * Get Validation form row
         */
        foreach ($this->rows() as $item) {
            if ($item->validation) {
                $validation[$item->name] = $item->validation;
            }
        }

        return $validation;
    }

    public  function  beforeValidation(Request $request): \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
    {
        $rules = $this->validation($request);

        $validator = Validator::make($request->all(), $rules);
        $validator->validate();

        return $validator;
    }

    public  function beforeStore(Request $request, $settings){
        return $settings;
    }

    public function store(Request $request)
    {
        /*
         * Load Validation
         */
        $validator = $this->beforeValidation($request);

        if (!$validator->fails()) {

            /*
             * Get Current Setting Class
             */
            $settings = new $this->setting();

            /*
             * Run hook before action
             */
            $settings = $this->beforeStore($request, $settings);

            /*
             * Get Rows
             */
            foreach($this->rows() as $item){

                /*
                 * Upload media if row is file
                 */
                if($item->vue === 'ViltMedia.vue'){
                    if ($request->hasFile($item->name)) {
                        foreach ($request->file($item->name) as $file){
                            $settings->{$item->name} = str_replace('public/', 'storage/', $file->store('public/settings'));
                        }
                    }
                }
                /*
                 * Save a col if has data
                 */
                else if($item->vue === 'ViltSwitch.vue'){
                    $settings->{$item->name} = (bool)$request->get($item->name);
                }
                else {
                    if($request->has($item->name) && ((!empty($request->get($item->name))) || is_array($request->get($item->name)))){
                        $settings->{$item->name} = $request->get($item->name);
                    }
                }
            }

            /*
             * Save Setting
             */
            $settings->save();

            /*
             * Run After Store Hook
             */
            $this->afterStore($request, $settings);

            /*
             * Return message
             */
            return Alert::make(__("Settings Updated Success!"))->fire();
        }
    }

    public  function afterStore(): void {}

    public function routes()
    {
        return Router::make($this->path)->middleware(['auth:sanctum'])->controller(static::class)->custom($this->route())->api($this->api)->settings(true)->get();
    }

    public function menus(): array
    {
        $menus = [
             Menu::make(Str::ucfirst($this->table))->group($this->group)->label($this->table . '.sidebar')->icon($this->icon)->route('admin.settings.'.$this->table . '.index')->can('view_'.$this->table)
        ];
        return array_merge($menus, $this->menu());
    }

}
