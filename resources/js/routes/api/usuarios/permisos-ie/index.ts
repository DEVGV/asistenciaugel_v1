import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::sync
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
export const sync = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sync.url(args, options),
    method: 'post',
})

sync.definition = {
    methods: ["post"],
    url: '/api/usuarios/{usuario}/permisos-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::sync
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
sync.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return sync.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::sync
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
sync.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sync.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::sync
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
const syncForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::sync
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:53
* @route '/api/usuarios/{usuario}/permisos-ie'
*/
syncForm.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(args, options),
    method: 'post',
})

sync.form = syncForm

const permisosIe = {
    sync: Object.assign(sync, sync),
}

export default permisosIe