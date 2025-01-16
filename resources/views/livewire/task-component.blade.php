<div wire:poll="getTask">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <button type="button" class="mr-3 mb-4 text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline"
                    wire:click="openCreateModal">Crear Tarea</button>

                    <table class="w-full text-md bg-white  rounded mb-4 text-center">
                        <thead class="text-white">
                                <tr class="border-b bg-indigo-500">

                                    <th class=" p-3 px-5">Nombre</th>
                                    <th class=" p-3 px-5">Descripcion</th>
                                    <th class="p-3 px-5">Accion</th>
                                </tr>


                        </thead>
                        <tbody class="flex-1 sm:flex-none">
                            @foreach ($tasks as $task)
                            <tr class="border-b hover:bg-indigo-100 bg-gray-100">
                                <td class="p-3 px-5 ">
                                    {{$task->title}}
                                </td>
                                <td class="p-3 px-5">
                                    {{$task->description}}
                                </td>
                                <td class="p-3 px-5 flex justify-center">
                                    @if (isset($task->pivot) && ($task->pivot->permission == 'owner' || $task->pivot->permission == 'edit'))
                                        
                                        <button type="button" wire:click="openCreateModal({{$task}})" class="mr-3 text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Actualizar</button>
                                        <button type="button" wire:click="deleteTask({{$task}})" class="text-sm mr-3 bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline" wire:confirm="¿Deseas borrar la tarea?"
                                        >
                                            Eliminar
                                        </button>
                                        <button type="button" wire:click="openShareModal({{$task}})" class=" mr-3 text-sm bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Compartir</button>
                                            @if ($task->shared && isset($task->pivot) && $task->pivot->permission == 'owner')
                                                <button wire:click="unShareTask({{ $task }})"
                                                    class="bg-gray-600 hover:bg-gray-500 text-white px-2 py-1 rounded-md text-sm">
                                                    Descompartir
                                                </button>
                                            @endif
                                    @endif
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                   
                </div>
            </div>
            
            
        </div>
    </div>
    <!-- component MODAL -->
    @if ($modal)
    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
        <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
            <div class="w-full">
                <div class="m-8 my-20 max-w-[400px] mx-auto">
                    <div class="mb-8">
                        <h1 class="mb-4 text-3xl font-extrabold">
                           {{isset($this->editingTask->id)? 'Actualizar':'Crear nueva'}} tarea
                        </h1>
                        <form>
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                                    <input type="text" wire:model="title" name="title" id="title"
                                        autocomplete="title"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea type="text" wire:model="description" name="description" id="description" autocomplete="description"
                                        rows="3"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="space-y-4">
                        <button class="p-3 bg-black rounded-full text-white w-full font-semibold"
                        wire:click="createTask">
                        {{isset($this->editingTask->id)? 'Actualizar':'Crear'}} tarea
                    </button>
                        <button class="p-3 bg-white border rounded-full w-full font-semibold"
                        wire:click="closeCreateModal">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- component MODAL -->
    @if ($modalShare)
    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
        <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
            <div class="w-full">
                <div class="m-8 my-20 max-w-[400px] mx-auto">
                    <div class="mb-8">
                        <h1 class="mb-4 text-3xl font-extrabold">Compartir Tarea</h1>
                        <form>
                            <div class="space-y-4">
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Título</label>
                                   <select wire:model="user_id" name="" id="" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Seleccione un usuario</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                   </select>
                                </div>
                                <div>
                                    <label for="permission" class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <select wire:model="permission" name="" id="permission" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Seleccione un permiso</option>
                                                <option value="view">Lectura</option>
                                                <option value="edit">Editar</option>

                                   </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="space-y-4">
                        <button class="p-3 bg-black rounded-full text-white w-full font-semibold"
                        wire:click="shareTask">
                        Compatir
                    </button>
                        <button class="p-3 bg-white border rounded-full w-full font-semibold"
                        wire:click="closeShareModal">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
