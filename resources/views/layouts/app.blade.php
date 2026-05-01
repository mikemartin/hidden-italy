<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-text font-sans flex flex-col antialiased">
        {!! \Statamic\Facades\Antlers::parse('{{ partial:layout/announcement_banner }}{{ partial:layout/header }}') !!}

        <main class="flex-1 fluid-grid py-12 lg:py-16">
            <div class="span-content">
                {{ $slot }}
            </div>
        </main>

        {!! \Statamic\Facades\Antlers::parse('{{ partial:layout/footer }}') !!}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
