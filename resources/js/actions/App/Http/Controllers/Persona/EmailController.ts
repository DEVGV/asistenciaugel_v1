import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\EmailController::store
* @see app/Http/Controllers/Persona/EmailController.php:27
* @route '/personas/{persona}/emails'
*/
export const store = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/personas/{persona}/emails',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Persona\EmailController::store
* @see app/Http/Controllers/Persona/EmailController.php:27
* @route '/personas/{persona}/emails'
*/
store.url = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { persona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { persona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            persona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        persona: typeof args.persona === 'object'
        ? args.persona.id
        : args.persona,
    }

    return store.definition.url
            .replace('{persona}', parsedArgs.persona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\EmailController::store
* @see app/Http/Controllers/Persona/EmailController.php:27
* @route '/personas/{persona}/emails'
*/
store.post = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\EmailController::store
* @see app/Http/Controllers/Persona/EmailController.php:27
* @route '/personas/{persona}/emails'
*/
const storeForm = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\EmailController::store
* @see app/Http/Controllers/Persona/EmailController.php:27
* @route '/personas/{persona}/emails'
*/
storeForm.post = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
*/
export const update = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/emails/{email}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
*/
update.put = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
*/
update.patch = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::update
* @see app/Http/Controllers/Persona/EmailController.php:34
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::destroy
* @see app/Http/Controllers/Persona/EmailController.php:48
* @route '/emails/{email}'
*/
export const destroy = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/emails/{email}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Persona\EmailController::destroy
* @see app/Http/Controllers/Persona/EmailController.php:48
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::destroy
* @see app/Http/Controllers/Persona/EmailController.php:48
* @route '/emails/{email}'
*/
destroy.delete = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Persona\EmailController::destroy
* @see app/Http/Controllers/Persona/EmailController.php:48
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::destroy
* @see app/Http/Controllers/Persona/EmailController.php:48
* @route '/emails/{email}'
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
* @see \App\Http\Controllers\Persona\EmailController::darDeBaja
* @see app/Http/Controllers/Persona/EmailController.php:41
* @route '/emails/{email}/dar-de-baja'
*/
export const darDeBaja = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/emails/{email}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Persona\EmailController::darDeBaja
* @see app/Http/Controllers/Persona/EmailController.php:41
* @route '/emails/{email}/dar-de-baja'
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
* @see \App\Http\Controllers\Persona\EmailController::darDeBaja
* @see app/Http/Controllers/Persona/EmailController.php:41
* @route '/emails/{email}/dar-de-baja'
*/
darDeBaja.patch = (args: { email: string | number | { id: string | number } } | [email: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\EmailController::darDeBaja
* @see app/Http/Controllers/Persona/EmailController.php:41
* @route '/emails/{email}/dar-de-baja'
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
* @see \App\Http\Controllers\Persona\EmailController::darDeBaja
* @see app/Http/Controllers/Persona/EmailController.php:41
* @route '/emails/{email}/dar-de-baja'
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

const EmailController = { store, update, destroy, darDeBaja }

export default EmailController