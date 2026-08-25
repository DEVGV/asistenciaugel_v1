import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
export const update = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/domicilios-ie/{domicilio}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
update.url = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { domicilio: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { domicilio: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            domicilio: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        domicilio: typeof args.domicilio === 'object'
        ? args.domicilio.id
        : args.domicilio,
    }

    return update.definition.url
            .replace('{domicilio}', parsedArgs.domicilio.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
update.put = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
update.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
const updateForm = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
updateForm.put = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
updateForm.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
export const destroy = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/domicilios-ie/{domicilio}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
destroy.url = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { domicilio: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { domicilio: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            domicilio: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        domicilio: typeof args.domicilio === 'object'
        ? args.domicilio.id
        : args.domicilio,
    }

    return destroy.definition.url
            .replace('{domicilio}', parsedArgs.domicilio.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
destroy.delete = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
const destroyForm = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
destroyForm.delete = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const domiciliosIe = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default domiciliosIe