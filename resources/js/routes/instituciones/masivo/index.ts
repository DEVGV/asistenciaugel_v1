import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaMasivaController.php:16
* @route '/instituciones-masivas'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones-masivas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaMasivaController.php:16
* @route '/instituciones-masivas'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaMasivaController.php:16
* @route '/instituciones-masivas'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaMasivaController.php:16
* @route '/instituciones-masivas'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaMasivaController.php:16
* @route '/instituciones-masivas'
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