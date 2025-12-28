<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information, education level, resume, and career interests.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
            <x-input-label for="resume" :value="__('Resume (PDF)')" />
            
            @if($user->resume_path)
                <div class="flex items-center mb-3 text-sm text-blue-600 font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <a href="{{ asset('storage/' . $user->resume_path) }}" target="_blank" class="hover:underline">
                        {{ __('View Current Resume') }}
                    </a>
                </div>
            @endif

            <input id="resume" name="resume" type="file" accept=".pdf" 
                class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100 transition cursor-pointer" />
            
            <p class="mt-2 text-xs text-gray-500 italic">
                {{ __('Upload a PDF version of your resume to keep it saved on your profile.') }}
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('resume')" />
        </div>

        <div>
            <x-input-label for="education_level" :value="__('Education Level')" />
            <x-text-input id="education_level" name="education_level" type="text" class="mt-1 block w-full" :value="old('education_level', $user->education_level)" placeholder="e.g. Bachelor of Computer Science" />
            <x-input-error class="mt-2" :messages="$errors->get('education_level')" />
        </div>

        <div>
            <x-input-label for="interests" :value="__('Career Interests')" />
            <x-text-input id="interests" name="interests" type="text" class="mt-1 block w-full" :value="old('interests', $user->interests)" placeholder="e.g. AI, Backend Development, Data Science" />
            <p class="mt-1 text-xs text-gray-500 italic">{{ __('Separate interests with commas.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('interests')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>