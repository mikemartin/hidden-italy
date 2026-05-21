@props(['hero' => '/assets/guided/campania-and-the-amalfi-coast/22.6.jpg'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-text font-sans antialiased">
        <div class="grid min-h-dvh lg:grid-cols-2">
            <div class="relative flex flex-col items-center justify-center px-6 py-12 lg:px-16 overflow-hidden">
                <s:partial src="components/topographic" />

                <div class="relative w-full max-w-sm space-y-10">
                    <div class="flex justify-center">
                        <s:partial src="components/logo" class="h-10 text-foreground" />
                    </div>

                    {{ $slot }}
                </div>
            </div>

            <div
                class="hidden lg:block bg-cover bg-center"
                style="background-image: url('{{ $hero }}');"
                aria-hidden="true"
            ></div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
