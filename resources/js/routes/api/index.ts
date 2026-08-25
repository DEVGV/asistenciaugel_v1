import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import mobile from './mobile'
import params from './params'
import sunat from './sunat'
import personas from './personas'
import trabajadores from './trabajadores'
import usuarios from './usuarios'
import zonas from './zonas'
import entidades from './entidades'
import instituciones from './instituciones'
import expedientes from './expedientes'
/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
export const registroDirecto = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registroDirecto.url(options),
    method: 'post',
})

registroDirecto.definition = {
    methods: ["post"],
    url: '/api/registro-directo',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
registroDirecto.url = (options?: RouteQueryOptions) => {
    return registroDirecto.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
registroDirecto.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registroDirecto.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
const registroDirectoForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registroDirecto.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
registroDirectoForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registroDirecto.url(options),
    method: 'post',
})

registroDirecto.form = registroDirectoForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
export const motivosSuspension = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosSuspension.url(options),
    method: 'get',
})

motivosSuspension.definition = {
    methods: ["get","head"],
    url: '/api/motivos-suspension',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspension.url = (options?: RouteQueryOptions) => {
    return motivosSuspension.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspension.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosSuspension.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspension.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosSuspension.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
const motivosSuspensionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosSuspension.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspensionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosSuspension.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspensionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosSuspension.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosSuspension.form = motivosSuspensionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
export const motivosIncapacidad = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosIncapacidad.url(options),
    method: 'get',
})

motivosIncapacidad.definition = {
    methods: ["get","head"],
    url: '/api/motivos-incapacidad',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidad.url = (options?: RouteQueryOptions) => {
    return motivosIncapacidad.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidad.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosIncapacidad.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidad.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosIncapacidad.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
const motivosIncapacidadForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosIncapacidad.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidadForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosIncapacidad.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidadForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosIncapacidad.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosIncapacidad.form = motivosIncapacidadForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
export const motivosJustificacion = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosJustificacion.url(options),
    method: 'get',
})

motivosJustificacion.definition = {
    methods: ["get","head"],
    url: '/api/motivos-justificacion',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacion.url = (options?: RouteQueryOptions) => {
    return motivosJustificacion.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacion.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosJustificacion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacion.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosJustificacion.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
const motivosJustificacionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosJustificacion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosJustificacion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosJustificacion.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosJustificacion.form = motivosJustificacionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
export const motivosExoneracion = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosExoneracion.url(options),
    method: 'get',
})

motivosExoneracion.definition = {
    methods: ["get","head"],
    url: '/api/motivos-exoneracion',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracion.url = (options?: RouteQueryOptions) => {
    return motivosExoneracion.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracion.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosExoneracion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracion.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosExoneracion.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
const motivosExoneracionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosExoneracion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosExoneracion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosExoneracion.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosExoneracion.form = motivosExoneracionForm

const api = {
    mobile: Object.assign(mobile, mobile),
    params: Object.assign(params, params),
    sunat: Object.assign(sunat, sunat),
    personas: Object.assign(personas, personas),
    trabajadores: Object.assign(trabajadores, trabajadores),
    usuarios: Object.assign(usuarios, usuarios),
    zonas: Object.assign(zonas, zonas),
    entidades: Object.assign(entidades, entidades),
    instituciones: Object.assign(instituciones, instituciones),
    expedientes: Object.assign(expedientes, expedientes),
    registroDirecto: Object.assign(registroDirecto, registroDirecto),
    motivosSuspension: Object.assign(motivosSuspension, motivosSuspension),
    motivosIncapacidad: Object.assign(motivosIncapacidad, motivosIncapacidad),
    motivosJustificacion: Object.assign(motivosJustificacion, motivosJustificacion),
    motivosExoneracion: Object.assign(motivosExoneracion, motivosExoneracion),
}

export default api