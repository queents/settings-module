<?php

namespace Modules\Settings\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateSettings extends Command
{

    protected  $stubPath;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'vilt:setting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make action file inside VILT resource actions.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stubPath = module_path('Settings') . '/stubs';
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settingName=$this->ask('Please input your setting page name? (ex: Sites)');
        $moduleName=$this->ask('Please input your module name? (ex: Translations)');

        /*
         * Generate Setting Class
         */
        $this->generateStubs(
            $this->stubPath . '/SettingsClass.stub',
            "Modules/" . $moduleName . '/Settings/' . $settingName . 'Settings.php',
            [
                'settingName' => $settingName,
                'moduleName' => $moduleName,
                'settingField' => Str::lower($settingName)
            ],
            [
                "Modules/" . $moduleName . '/Settings/'
            ]
        );
        $this->info('Setting Class Has Been Generated Success :)');
        /*
         * Generate Setting Migration
         */
        $this->generateStubs(
            $this->stubPath . '/SettingsMigration.stub',
            "Modules/" . $moduleName . '/Database/Migrations/' .date('Y_m_d_His').'_'. Str::lower($settingName) . '_settings.php',
            [
                'settingName' => $settingName,
                'settingField' => Str::lower($settingName)
            ]
        );
        $this->info('Setting Migrations Has Been Generated Success :)');

        /*
         * Generate Setting View
         */
        $this->generateStubs(
            $this->stubPath . '/ModulePage.stub',
            "Modules/" . $moduleName . '/Pages/' .$settingName. 'SettingsPage.php',
            [
                'moduleName' => $moduleName,
                'settingName' => $settingName,
                'settingClass' => $settingName . 'Settings',
                'settingField' => Str::lower($settingName)
            ],
            [
                "Modules/" . $moduleName . '/Pages/'
            ]
        );
        $this->info('Setting Page Has Been Generated Success :)');
    }

    protected function generateStubs(string $from, string $to,array $replacements, array $directory=[]): void
    {
        if(File::exists($from)){
            $stubValue = File::get($from);

            $convertStubToText = Str::of($stubValue);

            foreach($replacements as $key=>$replacement){
                $convertStubToText = $convertStubToText->replace('{{ '.$key.' }}',$replacement);
            }

            foreach($directory as $dir){
                if(!File::exists($dir)){
                    File::makeDirectory($dir);
                }
            }

            if(File::exists($to)){
                File::delete($to);
            }

            File::put($to, $convertStubToText);
        }
    }
}
