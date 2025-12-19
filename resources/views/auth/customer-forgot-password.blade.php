<x-guest-layout>
    <h2 class="text-2xl font-bold mb-4">Lupa Password Customer</h2>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-500">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('customer.password.email') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            Kirim link reset
        </x-primary-button>
    </form>
</x-guest-layout>
