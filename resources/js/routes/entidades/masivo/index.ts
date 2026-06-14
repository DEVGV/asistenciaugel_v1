import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Entidad\EntidadMasivaController::store
* @see app/Http/Controllers/Entidad/EntidadMasivaController.php:16
* @route '/entidades-masivas'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/entidades-masivas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Entidad\EntidadMasivaController::store
* @see app/Http/Controllers/Entidad/EntidadMasivaController.php:16
* @route '/entidades-masivas'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Entidad\EntidadMasivaController::store
* @see app/Http/Controllers/Entidad/EntidadMasivaController.php:16
* @route '/entidades-masivas'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadMasivaController::store
* @see app/Http/Controllers/Entidad/EntidadMasivaController.php:16
* @route '/entidades-masivas'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadMasivaController::store
* @see app/Http/Controllers/Entidad/EntidadMasivaController.php:16
* @route '/entidades-masivas'
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