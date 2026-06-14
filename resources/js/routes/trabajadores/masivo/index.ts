import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/trabajadores-masivos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const masivo = {
    store: Object.assign(store, store),
}

export default masivo