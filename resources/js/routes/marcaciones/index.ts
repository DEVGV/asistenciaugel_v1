import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::cargarExcel
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
export const cargarExcel = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cargarExcel.url(options),
    method: 'post',
})

cargarExcel.definition = {
    methods: ["post"],
    url: '/marcaciones/cargar-excel',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::cargarExcel
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
cargarExcel.url = (options?: RouteQueryOptions) => {
    return cargarExcel.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::cargarExcel
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
cargarExcel.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cargarExcel.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::cargarExcel
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
const cargarExcelForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cargarExcel.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Marcacion\CargaMarcacionesController::cargarExcel
* @see app/Http/Controllers/Marcacion/CargaMarcacionesController.php:16
* @route '/marcaciones/cargar-excel'
*/
cargarExcelForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cargarExcel.url(options),
    method: 'post',
})

cargarExcel.form = cargarExcelForm

const marcaciones = {
    cargarExcel: Object.assign(cargarExcel, cargarExcel),
}

export default marcaciones