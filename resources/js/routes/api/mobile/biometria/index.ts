import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:130
* @route '/api/mobile/biometria/enrolar-rostro'
*/
export const enrollFace = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enrollFace.url(options),
    method: 'post',
})

enrollFace.definition = {
    methods: ["post"],
    url: '/api/mobile/biometria/enrolar-rostro',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:130
* @route '/api/mobile/biometria/enrolar-rostro'
*/
enrollFace.url = (options?: RouteQueryOptions) => {
    return enrollFace.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:130
* @route '/api/mobile/biometria/enrolar-rostro'
*/
enrollFace.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enrollFace.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:130
* @route '/api/mobile/biometria/enrolar-rostro'
*/
const enrollFaceForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enrollFace.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:130
* @route '/api/mobile/biometria/enrolar-rostro'
*/
enrollFaceForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enrollFace.url(options),
    method: 'post',
})

enrollFace.form = enrollFaceForm

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocal
* @see app/Http/Controllers/Api/MobileController.php:169
* @route '/api/mobile/biometria/local-device/habilitar'
*/
export const enableLocal = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enableLocal.url(options),
    method: 'post',
})

enableLocal.definition = {
    methods: ["post"],
    url: '/api/mobile/biometria/local-device/habilitar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocal
* @see app/Http/Controllers/Api/MobileController.php:169
* @route '/api/mobile/biometria/local-device/habilitar'
*/
enableLocal.url = (options?: RouteQueryOptions) => {
    return enableLocal.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocal
* @see app/Http/Controllers/Api/MobileController.php:169
* @route '/api/mobile/biometria/local-device/habilitar'
*/
enableLocal.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enableLocal.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocal
* @see app/Http/Controllers/Api/MobileController.php:169
* @route '/api/mobile/biometria/local-device/habilitar'
*/
const enableLocalForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enableLocal.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocal
* @see app/Http/Controllers/Api/MobileController.php:169
* @route '/api/mobile/biometria/local-device/habilitar'
*/
enableLocalForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enableLocal.url(options),
    method: 'post',
})

enableLocal.form = enableLocalForm

const biometria = {
    enrollFace: Object.assign(enrollFace, enrollFace),
    enableLocal: Object.assign(enableLocal, enableLocal),
}

export default biometria