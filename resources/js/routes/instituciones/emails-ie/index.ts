import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
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

const emailsIe = {
    store: Object.assign(store, store),
    darDeBaja: Object.assign(darDeBaja, darDeBaja),
}

export default emailsIe