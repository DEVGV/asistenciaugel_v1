import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:183
* @route '/api/mobile/marcaciones/prevalidar'
*/
export const prevalidate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: prevalidate.url(options),
    method: 'post',
})

prevalidate.definition = {
    methods: ["post"],
    url: '/api/mobile/marcaciones/prevalidar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:183
* @route '/api/mobile/marcaciones/prevalidar'
*/
prevalidate.url = (options?: RouteQueryOptions) => {
    return prevalidate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:183
* @route '/api/mobile/marcaciones/prevalidar'
*/
prevalidate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: prevalidate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:183
* @route '/api/mobile/marcaciones/prevalidar'
*/
const prevalidateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: prevalidate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:183
* @route '/api/mobile/marcaciones/prevalidar'
*/
prevalidateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: prevalidate.url(options),
    method: 'post',
})

prevalidate.form = prevalidateForm

/**
* @see \App\Http\Controllers\Api\MobileController::store
* @see app/Http/Controllers/Api/MobileController.php:206
* @route '/api/mobile/marcaciones'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/mobile/marcaciones',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::store
* @see app/Http/Controllers/Api/MobileController.php:206
* @route '/api/mobile/marcaciones'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::store
* @see app/Http/Controllers/Api/MobileController.php:206
* @route '/api/mobile/marcaciones'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::store
* @see app/Http/Controllers/Api/MobileController.php:206
* @route '/api/mobile/marcaciones'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::store
* @see app/Http/Controllers/Api/MobileController.php:206
* @route '/api/mobile/marcaciones'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/mobile/marcaciones',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:332
* @route '/api/mobile/marcaciones'
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

const marcaciones = {
    prevalidate: Object.assign(prevalidate, prevalidate),
    store: Object.assign(store, store),
    index: Object.assign(index, index),
}

export default marcaciones