import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:24
* @route '/api/mobile/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

login.definition = {
    methods: ["post"],
    url: '/api/mobile/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:24
* @route '/api/mobile/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:24
* @route '/api/mobile/login'
*/
login.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:24
* @route '/api/mobile/login'
*/
const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: login.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:24
* @route '/api/mobile/login'
*/
loginForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: login.url(options),
    method: 'post',
})

login.form = loginForm

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
export const me = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: me.url(options),
    method: 'get',
})

me.definition = {
    methods: ["get","head"],
    url: '/api/mobile/me',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
me.url = (options?: RouteQueryOptions) => {
    return me.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
me.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: me.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
me.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: me.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
const meForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: me.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
meForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: me.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:70
* @route '/api/mobile/me'
*/
meForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: me.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

me.form = meForm

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:61
* @route '/api/mobile/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/api/mobile/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:61
* @route '/api/mobile/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:61
* @route '/api/mobile/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:61
* @route '/api/mobile/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:61
* @route '/api/mobile/logout'
*/
logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

logout.form = logoutForm

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:110
* @route '/api/mobile/biometria/enrolar-rostro'
*/
export const enrollFace = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enrollFace.url(options),
    method: 'post',
})

enrollFace.definition = {
    methods: ["post"],
    url: '/api/mobile/biometria/enrolar-rostro',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:110
* @route '/api/mobile/biometria/enrolar-rostro'
*/
enrollFace.url = (options?: RouteQueryOptions) => {
    return enrollFace.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:110
* @route '/api/mobile/biometria/enrolar-rostro'
*/
enrollFace.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enrollFace.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:110
* @route '/api/mobile/biometria/enrolar-rostro'
*/
const enrollFaceForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enrollFace.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enrollFace
* @see app/Http/Controllers/Api/MobileController.php:110
* @route '/api/mobile/biometria/enrolar-rostro'
*/
enrollFaceForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enrollFace.url(options),
    method: 'post',
})

enrollFace.form = enrollFaceForm

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocalBiometric
* @see app/Http/Controllers/Api/MobileController.php:149
* @route '/api/mobile/biometria/local-device/habilitar'
*/
export const enableLocalBiometric = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enableLocalBiometric.url(options),
    method: 'post',
})

enableLocalBiometric.definition = {
    methods: ["post"],
    url: '/api/mobile/biometria/local-device/habilitar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocalBiometric
* @see app/Http/Controllers/Api/MobileController.php:149
* @route '/api/mobile/biometria/local-device/habilitar'
*/
enableLocalBiometric.url = (options?: RouteQueryOptions) => {
    return enableLocalBiometric.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocalBiometric
* @see app/Http/Controllers/Api/MobileController.php:149
* @route '/api/mobile/biometria/local-device/habilitar'
*/
enableLocalBiometric.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enableLocalBiometric.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocalBiometric
* @see app/Http/Controllers/Api/MobileController.php:149
* @route '/api/mobile/biometria/local-device/habilitar'
*/
const enableLocalBiometricForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enableLocalBiometric.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::enableLocalBiometric
* @see app/Http/Controllers/Api/MobileController.php:149
* @route '/api/mobile/biometria/local-device/habilitar'
*/
enableLocalBiometricForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enableLocalBiometric.url(options),
    method: 'post',
})

enableLocalBiometric.form = enableLocalBiometricForm

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:163
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
* @see app/Http/Controllers/Api/MobileController.php:163
* @route '/api/mobile/marcaciones/prevalidar'
*/
prevalidate.url = (options?: RouteQueryOptions) => {
    return prevalidate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:163
* @route '/api/mobile/marcaciones/prevalidar'
*/
prevalidate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: prevalidate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:163
* @route '/api/mobile/marcaciones/prevalidar'
*/
const prevalidateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: prevalidate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::prevalidate
* @see app/Http/Controllers/Api/MobileController.php:163
* @route '/api/mobile/marcaciones/prevalidar'
*/
prevalidateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: prevalidate.url(options),
    method: 'post',
})

prevalidate.form = prevalidateForm

/**
* @see \App\Http\Controllers\Api\MobileController::storeMark
* @see app/Http/Controllers/Api/MobileController.php:186
* @route '/api/mobile/marcaciones'
*/
export const storeMark = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeMark.url(options),
    method: 'post',
})

storeMark.definition = {
    methods: ["post"],
    url: '/api/mobile/marcaciones',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::storeMark
* @see app/Http/Controllers/Api/MobileController.php:186
* @route '/api/mobile/marcaciones'
*/
storeMark.url = (options?: RouteQueryOptions) => {
    return storeMark.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::storeMark
* @see app/Http/Controllers/Api/MobileController.php:186
* @route '/api/mobile/marcaciones'
*/
storeMark.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeMark.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::storeMark
* @see app/Http/Controllers/Api/MobileController.php:186
* @route '/api/mobile/marcaciones'
*/
const storeMarkForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeMark.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::storeMark
* @see app/Http/Controllers/Api/MobileController.php:186
* @route '/api/mobile/marcaciones'
*/
storeMarkForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeMark.url(options),
    method: 'post',
})

storeMark.form = storeMarkForm

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
export const marks = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marks.url(options),
    method: 'get',
})

marks.definition = {
    methods: ["get","head"],
    url: '/api/mobile/marcaciones',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
marks.url = (options?: RouteQueryOptions) => {
    return marks.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
marks.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marks.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
marks.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marks.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
const marksForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marks.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
marksForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marks.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::marks
* @see app/Http/Controllers/Api/MobileController.php:301
* @route '/api/mobile/marcaciones'
*/
marksForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marks.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

marks.form = marksForm

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
export const schedule = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schedule.url(options),
    method: 'get',
})

schedule.definition = {
    methods: ["get","head"],
    url: '/api/mobile/horario',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
schedule.url = (options?: RouteQueryOptions) => {
    return schedule.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
schedule.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schedule.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
schedule.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: schedule.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
const scheduleForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: schedule.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
scheduleForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: schedule.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::schedule
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
scheduleForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: schedule.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

schedule.form = scheduleForm

const MobileController = { login, me, logout, enrollFace, enableLocalBiometric, prevalidate, storeMark, marks, schedule }

export default MobileController