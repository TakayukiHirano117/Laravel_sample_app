<x-layouts.book-manager>
    <x-slot:title>
        書籍更新
    </x-slot:title>

    <h1>
        書籍更新
    </h1>
    @if($errors->any())
        <x-alert class="danger">
            <x-error-messages :errors="$errors" />
        </x-alert>
    @endif
    
</x-layouts.book-manager>
