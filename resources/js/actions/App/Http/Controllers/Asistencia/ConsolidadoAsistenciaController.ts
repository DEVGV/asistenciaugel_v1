import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::procesar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:24
* @route '/instituciones/{institucione}/consolidado-asistencia/procesar'
*/
export const procesar = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: procesar.url(args, options),
    method: 'post',
})

procesar.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/consolidado-asistencia/procesar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::procesar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:24
* @route '/instituciones/{institucione}/consolidado-asistencia/procesar'
*/
procesar.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return procesar.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::procesar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:24
* @route '/instituciones/{institucione}/consolidado-asistencia/procesar'
*/
procesar.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: procesar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::procesar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:24
* @route '/instituciones/{institucione}/consolidado-asistencia/procesar'
*/
const procesarForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: procesar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::procesar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:24
* @route '/instituciones/{institucione}/consolidado-asistencia/procesar'
*/
procesarForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: procesar.url(args, options),
    method: 'post',
})

procesar.form = procesarForm

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reprocesarTrabajador
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:50
* @route '/instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador'
*/
export const reprocesarTrabajador = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reprocesarTrabajador.url(args, options),
    method: 'post',
})

reprocesarTrabajador.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reprocesarTrabajador
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:50
* @route '/instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador'
*/
reprocesarTrabajador.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return reprocesarTrabajador.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reprocesarTrabajador
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:50
* @route '/instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador'
*/
reprocesarTrabajador.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reprocesarTrabajador.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reprocesarTrabajador
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:50
* @route '/instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador'
*/
const reprocesarTrabajadorForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reprocesarTrabajador.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reprocesarTrabajador
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:50
* @route '/instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador'
*/
reprocesarTrabajadorForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reprocesarTrabajador.url(args, options),
    method: 'post',
})

reprocesarTrabajador.form = reprocesarTrabajadorForm

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
export const consultar = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: consultar.url(args, options),
    method: 'get',
})

consultar.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/consolidado-asistencia/consultar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
consultar.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return consultar.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
consultar.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: consultar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
consultar.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: consultar.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
const consultarForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: consultar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
consultarForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: consultar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::consultar
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:72
* @route '/instituciones/{institucione}/consolidado-asistencia/consultar'
*/
consultarForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: consultar.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

consultar.form = consultarForm

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
export const detalle = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalle.url(args, options),
    method: 'get',
})

detalle.definition = {
    methods: ["get","head"],
    url: '/consolidado-asistencia/{asistencia}/detalle',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalle.url = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { asistencia: args }
    }

    if (Array.isArray(args)) {
        args = {
            asistencia: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        asistencia: args.asistencia,
    }

    return detalle.definition.url
            .replace('{asistencia}', parsedArgs.asistencia.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalle.get = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalle.head = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detalle.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
const detalleForm = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalleForm.get = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalleForm.head = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

detalle.form = detalleForm

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
export const reporte1 = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reporte1.url(args, options),
    method: 'get',
})

reporte1.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/consolidado-asistencia/reporte1',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
reporte1.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return reporte1.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
reporte1.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reporte1.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
reporte1.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reporte1.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
const reporte1Form = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reporte1.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
reporte1Form.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reporte1.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte1
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:128
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte1'
*/
reporte1Form.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reporte1.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

reporte1.form = reporte1Form

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
export const reporte2 = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reporte2.url(args, options),
    method: 'get',
})

reporte2.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/consolidado-asistencia/reporte2',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
reporte2.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return reporte2.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
reporte2.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reporte2.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
reporte2.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reporte2.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
const reporte2Form = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reporte2.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
reporte2Form.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reporte2.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::reporte2
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:146
* @route '/instituciones/{institucione}/consolidado-asistencia/reporte2'
*/
reporte2Form.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reporte2.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

reporte2.form = reporte2Form

const ConsolidadoAsistenciaController = { procesar, reprocesarTrabajador, consultar, detalle, reporte1, reporte2 }

export default ConsolidadoAsistenciaController