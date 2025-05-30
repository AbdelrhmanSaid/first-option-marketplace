<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ViewMakeCommand as Command;
use Symfony\Component\Console\Input\InputOption;

class ViewMakeCommand extends Command
{
    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $contents = parent::buildClass($name);

        parse_str($this->option('params') ?: '', $params);

        $params = \Illuminate\Support\Arr::dot($params);

        $contents = str_replace(
            array_map(fn ($key) => "{{ $key }}", array_keys($params)),
            array_values($params),
            $contents,
        );

        return $contents;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($template = $this->option('template')) {
            $templatesPath = str_replace(
                base_path(),
                '',
                app_path('Console/Commands/stubs/view-templates'),
            );

            // Normalize the template path
            $template = str_replace('.', '/', $template);

            return $this->resolveStubPath(
                "$templatesPath/$template.stub",
            );
        }

        return $this->resolveStubPath(
            '/stubs/view.stub',
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['template', 't', InputOption::VALUE_OPTIONAL, 'The template to use, if none is provided the default template will be used'],
            ['params', 'p', InputOption::VALUE_OPTIONAL, 'The params to replace in the template'],
        ]);
    }
}
