import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
export const asignar = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: asignar.url(args, options),
    method: 'post',
})

asignar.definition = {
    methods: ["post"],
    url: '/usuarios/{usuario}/perfiles',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
asignar.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return asignar.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
asignar.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: asignar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
const asignarForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: asignar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
asignarForm.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: asignar.url(args, options),
    method: 'post',
})

asignar.form = asignarForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
export const revocar = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revocar.url(args, options),
    method: 'delete',
})

revocar.definition = {
    methods: ["delete"],
    url: '/usuarios/{usuario}/perfiles/{perfilIe}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
revocar.url = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            usuario: args[0],
            perfilIe: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        usuario: typeof args.usuario === 'object'
        ? args.usuario.id
        : args.usuario,
        perfilIe: typeof args.perfilIe === 'object'
        ? args.perfilIe.id
        : args.perfilIe,
    }

    return revocar.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace('{perfilIe}', parsedArgs.perfilIe.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
revocar.delete = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revocar.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
const revocarForm = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: revocar.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocar
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
revocarForm.delete = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: revocar.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

revocar.form = revocarForm

const perfiles = {
    asignar: Object.assign(asignar, asignar),
    revocar: Object.assign(revocar, revocar),
}

export default perfiles