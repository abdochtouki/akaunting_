<x-layouts.auth>
    <x-slot name="title">
        {{ trans('auth.register_user') }}
    </x-slot>

    <x-slot name="content">
        <div>
            <img src="{{ asset('public/img/akaunting-logo-green.svg') }}" class="w-16" alt="Akaunting" />

            <h1 class="text-lg my-3">
                {{ trans('auth.register_user') }}
            </h1>
        </div>

        @if ($errors->any())
        <div class="w-full bg-red-100 text-red-600 p-3 rounded-sm font-semibold text-xs">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <x-form id="auth" route="register.store">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5 lg:h-64">

                <x-form.group.text
                    name="name"
                    label="{{ trans('Name') }}"
                    placeholder="{{ trans('Name') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-form.group.text
                    name="email"
                    label="{{ trans('Email') }}"
                    placeholder="{{ trans('Email') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-form.group.text  {{-- Add company name field here --}}
                                    name="company_name"
                                    label="{{ trans('Company Name') }}"
                                    placeholder="{{ trans('Company Name') }}"
                                    form-group-class="sm:col-span-6"
                                    input-group-class="input-group-alternative"
                />

                <x-form.group.password
                    name="password"
                    label="{{ trans('auth.password.pass') }}"
                    placeholder="{{ trans('auth.password.pass') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-form.group.password
                    name="password_confirmation"
                    label="{{ trans('auth.password.pass_confirm') }}"
                    placeholder="{{ trans('auth.password.pass') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-button
                    type="submit"
                    ::disabled="form.loading"
                    class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 sm:col-span-6"
                    override="class"
                    data-loading-text="{{ trans('general.loading') }}"
                >
                    <i v-if="form.loading" class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i>
                    <span :class="[{'opacity-0': form.loading}]">
                        {{ trans('auth.register') }}
                    </span>
                </x-button>
            </div>
        </x-form>
    </x-slot>

    <x-script folder="auth" file="common" />
</x-layouts.auth>
