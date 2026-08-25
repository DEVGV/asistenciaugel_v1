import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::store
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:62
* @route '/horarios-trabajador/manual'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/horarios-trabajador/manual',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::store
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:62
* @route '/horarios-trabajador/manual'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::store
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:62
* @route '/horarios-trabajador/manual'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::store
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:62
* @route '/horarios-trabajador/manual'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::store
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:62
* @route '/horarios-trabajador/manual'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const manual = {
    store: Object.assign(store, store),
}

export default manual