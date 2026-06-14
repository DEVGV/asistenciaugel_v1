import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\PersonaMasivaController::store
* @see app/Http/Controllers/Persona/PersonaMasivaController.php:16
* @route '/personas-masivas'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/personas-masivas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Persona\PersonaMasivaController::store
* @see app/Http/Controllers/Persona/PersonaMasivaController.php:16
* @route '/personas-masivas'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaMasivaController::store
* @see app/Http/Controllers/Persona/PersonaMasivaController.php:16
* @route '/personas-masivas'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaMasivaController::store
* @see app/Http/Controllers/Persona/PersonaMasivaController.php:16
* @route '/personas-masivas'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaMasivaController::store
* @see app/Http/Controllers/Persona/PersonaMasivaController.php:16
* @route '/personas-masivas'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const PersonaMasivaController = { store }

export default PersonaMasivaController