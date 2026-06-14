import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import permisosIe1a1b11 from './permisos-ie'
/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
export const datos = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: datos.url(args, options),
    method: 'get',
})

datos.definition = {
    methods: ["get","head"],
    url: '/api/usuarios/{usuario}/datos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
datos.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { usuario: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { usuario: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            usuario: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        usuario: typeof args.usuario === 'object'
        ? args.usuario.id
        : args.usuario,
    }

    return datos.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
datos.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: datos.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
datos.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: datos.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
const datosForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: datos.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
datosForm.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: datos.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::datos
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
datosForm.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: datos.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

datos.form = datosForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
export const permisosIe = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: permisosIe.url(args, options),
    method: 'get',
})

permisosIe.definition = {
    methods: ["get","head"],
    url: '/api/usuarios/{usuario}/permisos-ie',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
permisosIe.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { usuario: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { usuario: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            usuario: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        usuario: typeof args.usuario === 'object'
        ? args.usuario.id
        : args.usuario,
    }

    return permisosIe.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
permisosIe.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: permisosIe.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
permisosIe.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: permisosIe.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
const permisosIeForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: permisosIe.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
permisosIeForm.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: permisosIe.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::permisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:36
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
permisosIeForm.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: permisosIe.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

permisosIe.form = permisosIeForm

const usuarios = {
    datos: Object.assign(datos, datos),
    permisosIe: Object.assign(permisosIe, permisosIe1a1b11),
}

export default usuarios