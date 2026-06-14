import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/registro-trabajador',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::create
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:18
* @route '/registro-trabajador'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:23
* @route '/registro-trabajador'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/registro-trabajador',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:23
* @route '/registro-trabajador'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:23
* @route '/registro-trabajador'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:23
* @route '/registro-trabajador'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::store
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:23
* @route '/registro-trabajador'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::storeMasivo
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
export const storeMasivo = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeMasivo.url(options),
    method: 'post',
})

storeMasivo.definition = {
    methods: ["post"],
    url: '/trabajadores-masivos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::storeMasivo
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
storeMasivo.url = (options?: RouteQueryOptions) => {
    return storeMasivo.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::storeMasivo
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
storeMasivo.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeMasivo.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::storeMasivo
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
const storeMasivoForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeMasivo.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\RegistroTrabajadorController::storeMasivo
* @see app/Http/Controllers/Trabajador/RegistroTrabajadorController.php:31
* @route '/trabajadores-masivos'
*/
storeMasivoForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeMasivo.url(options),
    method: 'post',
})

storeMasivo.form = storeMasivoForm

const RegistroTrabajadorController = { create, store, storeMasivo }

export default RegistroTrabajadorController