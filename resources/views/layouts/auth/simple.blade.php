<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-text font-sans antialiased">
        <div class="relative flex min-h-dvh flex-col items-center justify-center gap-6 p-6 md:p-10 overflow-hidden">
            <s:partial src="components/topographic" />

            <div class="relative flex w-full max-w-sm flex-col gap-6">
                <div class="flex justify-center text-foreground">
                    <s:partial src="components/logo" class="h-10" />
                </div>

                {{ $slot }}
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
