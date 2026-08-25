import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::store
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:18
* @route '/instituciones/{institucione}/emails-ie'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/emails-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::store
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:18
* @route '/instituciones/{institucione}/emails-ie'
*/
store.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { institucione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { institucione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
    }

    return store.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::store
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:18
* @route '/instituciones/{institucione}/emails-ie'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::store
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:18
* @route '/instituciones/{institucione}/emails-ie'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::store
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:18
* @route '/instituciones/{institucione}/emails-ie'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

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

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:34
* @route '/instituciones-emails/{email}/dar-de-baja'
*/
export const darDeBaja = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/instituciones-emails/{email}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:34
* @route '/instituciones-emails/{email}/dar-de-baja'
*/
darDeBaja.url = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return darDeBaja.definition.url
            .replace('{email}', parsedArgs.email.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:34
* @route '/instituciones-emails/{email}/dar-de-baja'
*/
darDeBaja.patch = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:34
* @route '/instituciones-emails/{email}/dar-de-baja'
*/
const darDeBajaForm = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\EmailIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/EmailIEController.php:34
* @route '/instituciones-emails/{email}/dar-de-baja'
*/
darDeBajaForm.patch = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

darDeBaja.form = darDeBajaForm

const EmailIEController = { store, update, destroy, darDeBaja }

export default EmailIEController