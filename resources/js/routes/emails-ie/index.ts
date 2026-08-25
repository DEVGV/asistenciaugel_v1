import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
export const update = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/emails-ie/{email}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
update.url = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { email: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { email: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            email: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        email: typeof args.email === 'object'
        ? args.email.id
        : args.email,
    }

    return update.definition.url
            .replace('{email}', parsedArgs.email.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
update.put = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
update.patch = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
const updateForm = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
updateForm.put = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::update
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:26
* @route '/emails-ie/{email}'
*/
updateForm.patch = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:42
* @route '/emails-ie/{email}'
*/
export const destroy = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/emails-ie/{email}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:42
* @route '/emails-ie/{email}'
*/
destroy.url = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { email: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { email: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            email: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        email: typeof args.email === 'object'
        ? args.email.id
        : args.email,
    }

    return destroy.definition.url
            .replace('{email}', parsedArgs.email.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:42
* @route '/emails-ie/{email}'
*/
destroy.delete = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:42
* @route '/emails-ie/{email}'
*/
const destroyForm = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:42
* @route '/emails-ie/{email}'
*/
destroyForm.delete = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const emailsIe = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default emailsIe