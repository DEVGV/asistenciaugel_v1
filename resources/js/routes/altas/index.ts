import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/altas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
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
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
export const update = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/altas/{alta}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
update.url = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { alta: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { alta: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            alta: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        alta: typeof args.alta === 'object'
        ? args.alta.id
        : args.alta,
    }

    return update.definition.url
            .replace('{alta}', parsedArgs.alta.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
update.put = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
update.patch = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
const updateForm = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
updateForm.put = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
updateForm.patch = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::baja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
export const baja = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: baja.url(args, options),
    method: 'post',
})

baja.definition = {
    methods: ["post"],
    url: '/altas/{alta}/baja',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::baja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
baja.url = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { alta: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { alta: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            alta: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        alta: typeof args.alta === 'object'
        ? args.alta.id
        : args.alta,
    }

    return baja.definition.url
            .replace('{alta}', parsedArgs.alta.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::baja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
baja.post = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: baja.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::baja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
const bajaForm = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: baja.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::baja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
bajaForm.post = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: baja.url(args, options),
    method: 'post',
})

baja.form = bajaForm

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
export const destroy = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/altas/{alta}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
destroy.url = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { alta: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { alta: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            alta: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        alta: typeof args.alta === 'object'
        ? args.alta.id
        : args.alta,
    }

    return destroy.definition.url
            .replace('{alta}', parsedArgs.alta.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
destroy.delete = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
const destroyForm = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
destroyForm.delete = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const altas = {
    index: Object.assign(index, index),
    update: Object.assign(update, update),
    baja: Object.assign(baja, baja),
    destroy: Object.assign(destroy, destroy),
}

export default altas