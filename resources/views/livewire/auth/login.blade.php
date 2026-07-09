<x-mary-card>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">ورود به پنل مدیریت</h1>
        <p class="text-gray-500 mt-2">اطلاعات حساب خود را وارد کنید</p>
    </div>

    <form wire:submit="login" class="space-y-4">
        <x-mary-input 
            label="ایمیل" 
            wire:model="email" 
            type="email" 
            placeholder="email@example.com"
            dir="ltr"
            icon="o-envelope"
        />
        
        <x-mary-password 
            label="رمز عبور" 
            wire:model="password" 
            placeholder="********"
            dir="ltr"
            toggle
        />
        
        <div class="flex items-center justify-between">
            <x-mary-checkbox label="مرا به خاطر بسپار" wire:model="remember" />
            <a href="#" class="text-sm text-primary hover:underline">فراموشی رمز عبور؟</a>
        </div>
        
        <x-mary-button 
            type="submit" 
            label="ورود" 
            icon="o-arrow-right-start-on-rectangle" 
            class="btn-primary w-full"
            spinner
        />
    </form>

    <x-mary-errors icon="o-exclamation-triangle" class="my-4" />
</x-mary-card>