import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradosMasivaIEController.php:17
* @route '/instituciones/{institucione}/grados-masivos'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/grados-masivos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradosMasivaIEController.php:17
* @route '/instituciones/{institucione}/grados-masivos'
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
* @see \App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradosMasivaIEController.php:17
* @route '/instituciones/{institucione}/grados-masivos'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradosMasivaIEController.php:17
* @route '/instituciones/{institucione}/grados-masivos'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradosMasivaIEController.php:17
* @route '/instituciones/{institucione}/grados-masivos'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const GradosMasivaIEController = { store }

export default GradosMasivaIEController