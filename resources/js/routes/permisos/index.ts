import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/permisos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
export const validar = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validar.url(args, options),
    method: 'post',
})

validar.definition = {
    methods: ["post"],
    url: '/permisos/{expediente}/validar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
validar.url = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return validar.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
validar.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
const validarForm = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: validar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
validarForm.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: validar.url(args, options),
    method: 'post',
})

validar.form = validarForm

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
export const anular = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

anular.definition = {
    methods: ["post"],
    url: '/permisos/{expediente}/anular',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
anular.url = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return anular.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
anular.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
const anularForm = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
anularForm.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

anular.form = anularForm

const permisos = {
    store: Object.assign(store, store),
    validar: Object.assign(validar, validar),
    anular: Object.assign(anular, anular),
}

export default permisos