import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
export const update = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/dias-no-laborables/{diasNoLaborable}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
update.url = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { diasNoLaborable: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { diasNoLaborable: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            diasNoLaborable: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        diasNoLaborable: typeof args.diasNoLaborable === 'object'
        ? args.diasNoLaborable.id
        : args.diasNoLaborable,
    }

    return update.definition.url
            .replace('{diasNoLaborable}', parsedArgs.diasNoLaborable.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
update.put = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
update.patch = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
const updateForm = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
updateForm.put = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
updateForm.patch = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
export const destroy = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/dias-no-laborables/{diasNoLaborable}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
destroy.url = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { diasNoLaborable: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { diasNoLaborable: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            diasNoLaborable: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        diasNoLaborable: typeof args.diasNoLaborable === 'object'
        ? args.diasNoLaborable.id
        : args.diasNoLaborable,
    }

    return destroy.definition.url
            .replace('{diasNoLaborable}', parsedArgs.diasNoLaborable.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
destroy.delete = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
const destroyForm = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
destroyForm.delete = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const diasNoLaborables = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default diasNoLaborables