<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        
        toggleGroup(permissions) {
            let allSelected = permissions.every(id => this.state.includes(id));
            if (allSelected) {
                this.state = this.state.filter(id => !permissions.includes(id));
            } else {
                this.state = [...new Set([...this.state, ...permissions])];
            }
        },

        isGroupSelected(permissions) {
            return permissions.every(id => this.state.includes(id));
        }
    }">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($getGroupedPermissions() as $groupName => $permissions)
                @php
                    $permissionIds = collect($permissions)->pluck('id')->toArray();
                    $permissionIdsJson = json_encode($permissionIds);
                @endphp
                <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
                    <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-white/10 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400">
                                <x-heroicon-o-server-stack class="w-5 h-5" />
                            </div>
                            <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                                {{ $groupName }}
                            </h3>
                        </div>
                        <button 
                            type="button" 
                            x-on:click="toggleGroup({{ $permissionIdsJson }})"
                            class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"
                            x-text="isGroupSelected({{ $permissionIdsJson }}) ? 'Deselect All' : 'Select All'"
                        >
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($permissions as $permission)
                            <label class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors cursor-pointer group">
                                <div class="flex items-center h-5">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $permission['id'] }}" 
                                        x-model="state"
                                        class="fi-checkbox-input rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-primary-500 dark:focus:ring-primary-500 transition duration-75"
                                    >
                                </div>
                                <div class="text-sm leading-5">
                                    <span class="font-medium text-gray-700 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                        {{ $permission['label'] }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-dynamic-component>