import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/telefonos-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
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
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
export const darDeBaja = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/instituciones-telefonos/{telefono}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
darDeBaja.url = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telefono: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { telefono: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            telefono: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telefono: typeof args.telefono === 'object'
        ? args.telefono.id
        : args.telefono,
    }

    return darDeBaja.definition.url
            .replace('{telefono}', parsedArgs.telefono.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
darDeBaja.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
const darDeBajaForm = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
darDeBajaForm.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

darDeBaja.form = darDeBajaForm

const telefonosIe = {
    store: Object.assign(store, store),
    darDeBaja: Object.assign(darDeBaja, darDeBaja),
}

export default telefonosIe