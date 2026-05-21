import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\SunatController::ruc
* @see app/Http/Controllers/Api/SunatController.php:21
* @route '/api/sunat/ruc'
*/
export const ruc = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ruc.url(options),
    method: 'post',
})

ruc.definition = {
    methods: ["post"],
    url: '/api/sunat/ruc',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\SunatController::ruc
* @see app/Http/Controllers/Api/SunatController.php:21
* @route '/api/sunat/ruc'
*/
ruc.url = (options?: RouteQueryOptions) => {
    return ruc.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\SunatController::ruc
* @see app/Http/Controllers/Api/SunatController.php:21
* @route '/api/sunat/ruc'
*/
ruc.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ruc.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\SunatController::ruc
* @see app/Http/Controllers/Api/SunatController.php:21
* @route '/api/sunat/ruc'
*/
const rucForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: ruc.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\SunatController::ruc
* @see app/Http/Controllers/Api/SunatController.php:21
* @route '/api/sunat/ruc'
*/
rucForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: ruc.url(options),
    method: 'post',
})

ruc.form = rucForm

/**
* @see \App\Http\Controllers\Api\SunatController::dni
* @see app/Http/Controllers/Api/SunatController.php:45
* @route '/api/sunat/dni'
*/
export const dni = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: dni.url(options),
    method: 'post',
})

dni.definition = {
    methods: ["post"],
    url: '/api/sunat/dni',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\SunatController::dni
* @see app/Http/Controllers/Api/SunatController.php:45
* @route '/api/sunat/dni'
*/
dni.url = (options?: RouteQueryOptions) => {
    return dni.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\SunatController::dni
* @see app/Http/Controllers/Api/SunatController.php:45
* @route '/api/sunat/dni'
*/
dni.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: dni.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\SunatController::dni
* @see app/Http/Controllers/Api/SunatController.php:45
* @route '/api/sunat/dni'
*/
const dniForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: dni.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\SunatController::dni
* @see app/Http/Controllers/Api/SunatController.php:45
* @route '/api/sunat/dni'
*/
dniForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: dni.url(options),
    method: 'post',
})

dni.form = dniForm

const SunatController = { ruc, dni }

export default SunatController