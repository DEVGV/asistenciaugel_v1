import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/usuarios',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::index
* @see app/Http/Controllers/Configuracion/UsuarioController.php:24
* @route '/usuarios'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
export const show = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/usuarios/{usuario}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
show.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
show.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
show.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
const showForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
showForm.get = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::show
* @see app/Http/Controllers/Configuracion/UsuarioController.php:32
* @route '/usuarios/{usuario}'
*/
showForm.head = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::cambiarPassword
* @see app/Http/Controllers/Configuracion/UsuarioController.php:43
* @route '/usuarios/{usuario}/cambiar-password'
*/
export const cambiarPassword = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cambiarPassword.url(args, options),
    method: 'post',
})

cambiarPassword.definition = {
    methods: ["post"],
    url: '/usuarios/{usuario}/cambiar-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::cambiarPassword
* @see app/Http/Controllers/Configuracion/UsuarioController.php:43
* @route '/usuarios/{usuario}/cambiar-password'
*/
cambiarPassword.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return cambiarPassword.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::cambiarPassword
* @see app/Http/Controllers/Configuracion/UsuarioController.php:43
* @route '/usuarios/{usuario}/cambiar-password'
*/
cambiarPassword.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cambiarPassword.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::cambiarPassword
* @see app/Http/Controllers/Configuracion/UsuarioController.php:43
* @route '/usuarios/{usuario}/cambiar-password'
*/
const cambiarPasswordForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cambiarPassword.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::cambiarPassword
* @see app/Http/Controllers/Configuracion/UsuarioController.php:43
* @route '/usuarios/{usuario}/cambiar-password'
*/
cambiarPasswordForm.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cambiarPassword.url(args, options),
    method: 'post',
})

cambiarPassword.form = cambiarPasswordForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::toggleActivo
* @see app/Http/Controllers/Configuracion/UsuarioController.php:50
* @route '/usuarios/{usuario}/toggle-activo'
*/
export const toggleActivo = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleActivo.url(args, options),
    method: 'post',
})

toggleActivo.definition = {
    methods: ["post"],
    url: '/usuarios/{usuario}/toggle-activo',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::toggleActivo
* @see app/Http/Controllers/Configuracion/UsuarioController.php:50
* @route '/usuarios/{usuario}/toggle-activo'
*/
toggleActivo.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return toggleActivo.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::toggleActivo
* @see app/Http/Controllers/Configuracion/UsuarioController.php:50
* @route '/usuarios/{usuario}/toggle-activo'
*/
toggleActivo.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleActivo.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::toggleActivo
* @see app/Http/Controllers/Configuracion/UsuarioController.php:50
* @route '/usuarios/{usuario}/toggle-activo'
*/
const toggleActivoForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: toggleActivo.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::toggleActivo
* @see app/Http/Controllers/Configuracion/UsuarioController.php:50
* @route '/usuarios/{usuario}/toggle-activo'
*/
toggleActivoForm.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: toggleActivo.url(args, options),
    method: 'post',
})

toggleActivo.form = toggleActivoForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
export const asignarPerfil = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: asignarPerfil.url(args, options),
    method: 'post',
})

asignarPerfil.definition = {
    methods: ["post"],
    url: '/usuarios/{usuario}/perfiles',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
asignarPerfil.url = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return asignarPerfil.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
asignarPerfil.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: asignarPerfil.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
const asignarPerfilForm = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: asignarPerfil.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::asignarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:59
* @route '/usuarios/{usuario}/perfiles'
*/
asignarPerfilForm.post = (args: { usuario: number | { id: number } } | [usuario: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: asignarPerfil.url(args, options),
    method: 'post',
})

asignarPerfil.form = asignarPerfilForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
export const revocarPerfil = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revocarPerfil.url(args, options),
    method: 'delete',
})

revocarPerfil.definition = {
    methods: ["delete"],
    url: '/usuarios/{usuario}/perfiles/{perfilIe}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
revocarPerfil.url = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions) => {
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

    return revocarPerfil.definition.url
            .replace('{usuario}', parsedArgs.usuario.toString())
            .replace('{perfilIe}', parsedArgs.perfilIe.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
revocarPerfil.delete = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: revocarPerfil.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
const revocarPerfilForm = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: revocarPerfil.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioController::revocarPerfil
* @see app/Http/Controllers/Configuracion/UsuarioController.php:71
* @route '/usuarios/{usuario}/perfiles/{perfilIe}'
*/
revocarPerfilForm.delete = (args: { usuario: number | { id: number }, perfilIe: number | { id: number } } | [usuario: number | { id: number }, perfilIe: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: revocarPerfil.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

revocarPerfil.form = revocarPerfilForm

const UsuarioController = { index, show, cambiarPassword, toggleActivo, asignarPerfil, revocarPerfil }

export default UsuarioController