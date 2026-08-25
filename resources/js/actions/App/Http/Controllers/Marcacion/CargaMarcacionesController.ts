import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::store
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/marcaciones/cargar-excel',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::store
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::store
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::store
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::store
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const CargaMarcacionesController = { store }

export default CargaMarcacionesController