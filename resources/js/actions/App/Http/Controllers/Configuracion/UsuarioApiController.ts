import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
export const porTrabajador = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

porTrabajador.definition = {
    methods: ["get","head"],
    url: '/api/trabajadores/{trabajador}/usuario',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
porTrabajador.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return porTrabajador.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
porTrabajador.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
porTrabajador.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porTrabajador.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
const porTrabajadorForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
porTrabajadorForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porTrabajador
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
porTrabajadorForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

porTrabajador.form = porTrabajadorForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
export const porUsuario = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porUsuario.url(args, options),
    method: 'get',
})

porUsuario.definition = {
    methods: ["get","head"],
    url: '/api/usuarios/{usuario}/datos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
porUsuario.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return porUsuario.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
porUsuario.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porUsuario.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
porUsuario.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porUsuario.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
const porUsuarioForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porUsuario.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
porUsuarioForm.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porUsuario.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::porUsuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:27
* @route '/api/usuarios/{usuario}/datos'
*/
porUsuarioForm.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porUsuario.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

porUsuario.form = porUsuarioForm

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

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::syncPermisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
export const syncPermisosIe = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: syncPermisosIe.url(args, options),
    method: 'post',
})

syncPermisosIe.definition = {
    methods: ["post"],
    url: '/api/usuarios/{usuario}/permisos-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::syncPermisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
syncPermisosIe.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return syncPermisosIe.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::syncPermisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
syncPermisosIe.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: syncPermisosIe.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::syncPermisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
const syncPermisosIeForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: syncPermisosIe.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::syncPermisosIe
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
syncPermisosIeForm.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: syncPermisosIe.url(args, options),
    method: 'post',
})

syncPermisosIe.form = syncPermisosIeForm

const UsuarioApiController = { porTrabajador, porUsuario, permisosIe, syncPermisosIe }

export default UsuarioApiController