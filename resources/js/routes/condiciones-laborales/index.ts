import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/condiciones-laborales',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::index
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:21
* @route '/condiciones-laborales'
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
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::store
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:29
* @route '/condiciones-laborales'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/condiciones-laborales',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::store
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:29
* @route '/condiciones-laborales'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::store
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:29
* @route '/condiciones-laborales'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::store
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:29
* @route '/condiciones-laborales'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::store
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:29
* @route '/condiciones-laborales'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
export const update = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/condiciones-laborales/{condicionLaboral}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
update.url = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { condicionLaboral: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { condicionLaboral: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            condicionLaboral: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        condicionLaboral: typeof args.condicionLaboral === 'object'
        ? args.condicionLaboral.id
        : args.condicionLaboral,
    }

    return update.definition.url
            .replace('{condicionLaboral}', parsedArgs.condicionLaboral.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
update.put = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
update.patch = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
const updateForm = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
updateForm.put = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::update
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:37
* @route '/condiciones-laborales/{condicionLaboral}'
*/
updateForm.patch = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::destroy
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:45
* @route '/condiciones-laborales/{condicionLaboral}'
*/
export const destroy = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/condiciones-laborales/{condicionLaboral}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::destroy
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:45
* @route '/condiciones-laborales/{condicionLaboral}'
*/
destroy.url = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { condicionLaboral: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { condicionLaboral: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            condicionLaboral: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        condicionLaboral: typeof args.condicionLaboral === 'object'
        ? args.condicionLaboral.id
        : args.condicionLaboral,
    }

    return destroy.definition.url
            .replace('{condicionLaboral}', parsedArgs.condicionLaboral.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::destroy
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:45
* @route '/condiciones-laborales/{condicionLaboral}'
*/
destroy.delete = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::destroy
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:45
* @route '/condiciones-laborales/{condicionLaboral}'
*/
const destroyForm = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\CondicionLaboralController::destroy
* @see app/Http/Controllers/Configuracion/CondicionLaboralController.php:45
* @route '/condiciones-laborales/{condicionLaboral}'
*/
destroyForm.delete = (args: { condicionLaboral: string | number | { id: string | number } } | [condicionLaboral: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const condicionesLaborales = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default condicionesLaborales