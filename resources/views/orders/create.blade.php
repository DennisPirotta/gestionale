@extends('layouts.app')
@php
    use Carbon\Carbon;
    $require_navbar_tools = true;
    $first = $orders->sortByDesc('innerCode')->first();
    $code = substr($first->innerCode, 4);
    $year = substr($first->innerCode, 0,4);
    if (Carbon::now()->format('Y') > $year){
        $innerCode = Carbon::now()->format('Y') . "0000";
    }else{
        $innerCode = $first->innerCode;
    }
    $outerCode = $first->outerCode ?? null;
@endphp
@section('content')
    <div class="container my-5 p-5 shadow-sm">
        <div class="d-flex align-items-center">
            <span class="m-0 h1">Inserisci una nuova commessa</span>
        </div>
        <hr>
        <form method="post" action="/commesse" class="row mt-4">
            @csrf
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-123 me-2"></i>Codice Interno</span>
                    <input type="number" class="form-control" aria-label="Codice Interno" name="innerCode"
                           value="{{ $innerCode + 1 }}">
                </div>
                @error('innerCode')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-123 me-2"></i>Codice Esterno</span>
                    <input type="text" class="form-control" aria-label="Codice Esterno" name="outerCode"
                           value="{{ $outerCode ?? '' }}">
                </div>
                @error('outerCode')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-building me-2"></i>Compagnia</label>
                    <select class="form-select" id="inputGroupSelect01" name="company_id">
                        @foreach($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-lightning-charge me-2"></i>Stato</label>
                    <select class="form-select" id="inputGroupSelect01" name="status_id">
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->description}}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <p class="text-danger fs-6">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="select_box"><i
                                class="bi bi-globe2 me-2"></i>Paese</label>
                    <select name="country_id" class="form-select" id="select_box">
                        @foreach($countries as $country)
                            <option value="{{$country->id}}"
                                    @if($country->name === "Italy") selected @endif
                                    data-thumbnail="{{ asset('images/flags/' . strtolower($country->code)) }}.svg"
                            >
                                {{ __($country->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('country')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Descrizione</span>
                    <input type="text" class="form-control" aria-label="Descrizione" name="description"
                           value="{{ old('description') }}">
                </div>
                @error('description')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SW</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSW">
                </div>
                @error('hourSW')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore MS</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourMS"
                           value="{{ old('hourMS') }}">
                </div>
                @error('hourMS')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore FAT</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourFAT"
                           value="{{ old('hourFAT') }}">
                </div>
                @error('hourFAT')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SAF</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSAF"
                           value="{{ old('hourSAF') }}">
                </div>
                @error('hourSAF')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-person me-2"></i>Progresso</label>
                    <select class="form-select" id="inputGroupSelect01" name="job_type_id">
                        @unless(count($job_types) === 0)
                            @foreach($job_types as $job_type)
                                <option value="{{$job_type->id}}">{{$job_type->description}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                @error('progress')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-check me-2"></i>Apertura</span>
                    <input type="date" class="form-control" aria-label="Apertura" name="opening"
                           value="{{ Carbon::now()->format('Y-m-d') }}">
                </div>
                @error('opening')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-x me-2"></i>Chiusura</span>
                    <input type="date" class="form-control" aria-label="Chiusura" name="closing"
                           value="{{ old('closing') }}">
                </div>
                @error('closing')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-person me-2"></i>Cliente</label>
                    <select class="form-select" id="inputGroupSelect01" name="customer_id">
                        @unless(count($customers) === 0)
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                @error('customer_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-person me-2"></i>Responsabile</label>
                    <select class="form-select" id="inputGroupSelect01" name="user_id">
                        @unless(count($users) === 0)
                            @foreach($users as $user)
                                <option value="{{$user->id}}" @if($user->id === auth()->id()) selected @endif >{{$user->surname}} {{$user->name}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                @error('user_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Salva</button>
        </form>
    </div>
    <script>
        $(()=>{
            let select_box_element = document.querySelector('#select_box');
            dselect(select_box_element, {
                search: true
            });
            $('.dselect-wrapper').addClass('form-control')
        })
    </script>
    <script>
        function dselectUpdate(button, classElement, classToggler) {
            const value = button.dataset.dselectValue
            const target = button.closest(`.${classElement}`).previousElementSibling
            const toggler = target.nextElementSibling.getElementsByClassName(classToggler)[0]
            const input = target.nextElementSibling.querySelector('input')
            if (target.multiple) {
                Array.from(target.options).filter(option => option.value === value)[0].selected = true
            } else {
                target.value = value
            }
            if (target.multiple) {
                toggler.click()
            }
            target.dispatchEvent(new Event('change'))
            toggler.focus()
            if (input) {
                input.value = ''
            }
        }
        function dselectRemoveTag(button, classElement, classToggler) {
            const value = button.parentNode.dataset.dselectValue
            const target = button.closest(`.${classElement}`).previousElementSibling
            const toggler = target.nextElementSibling.getElementsByClassName(classToggler)[0]
            const input = target.nextElementSibling.querySelector('input')
            Array.from(target.options).filter(option => option.value === value)[0].selected = false
            target.dispatchEvent(new Event('change'))
            toggler.click()
            if (input) {
                input.value = ''
            }
        }
        function dselectSearch(event, input, classElement, classToggler, creatable) {
            const filterValue = input.value.toLowerCase().trim()
            const itemsContainer = input.nextElementSibling
            const headers = itemsContainer.querySelectorAll('.dropdown-header')
            const items = itemsContainer.querySelectorAll('.dropdown-item')
            const noResults = itemsContainer.nextElementSibling

            headers.forEach(i => i.classList.add('d-none'))

            for (const item of items) {
                const filterText = item.textContent

                if (filterText.toLowerCase().indexOf(filterValue) > -1) {
                    item.classList.remove('d-none')
                    let header = item
                    while(header = header.previousElementSibling) {
                        if (header.classList.contains('dropdown-header')) {
                            header.classList.remove('d-none')
                            break
                        }
                    }
                } else {
                    item.classList.add('d-none')
                }
            }
            const found = Array.from(items).filter(i => !i.classList.contains('d-none') && !i.hasAttribute('hidden'))
            if (found.length < 1) {
                noResults.classList.remove('d-none')
                itemsContainer.classList.add('d-none')
                if (creatable) {
                    noResults.innerHTML = `Press Enter to add "<strong>${input.value}</strong>"`
                    if (event.key === 'Enter') {
                        const target = input.closest(`.${classElement}`).previousElementSibling
                        const toggler = target.nextElementSibling.getElementsByClassName(classToggler)[0]
                        target.insertAdjacentHTML('afterbegin', `<option value="${input.value}" selected>${input.value}</option>`)
                        target.dispatchEvent(new Event('change'))
                        input.value = ''
                        input.dispatchEvent(new Event('keyup'))
                        toggler.click()
                        toggler.focus()
                    }
                }
            } else {
                noResults.classList.add('d-none')
                itemsContainer.classList.remove('d-none')
            }
        }
        function dselectClear(button, classElement) {
            const target = button.closest(`.${classElement}`).previousElementSibling
            Array.from(target.options).forEach(option => option.selected = false)
            target.dispatchEvent(new Event('change'))
        }
        function dselect(el, option = {}) {
            el.style.display = 'none'
            const classElement = 'dselect-wrapper'
            const classNoResults = 'dselect-no-results'
            const classTag = 'dselect-tag'
            const classTagRemove = 'dselect-tag-remove'
            const classPlaceholder = 'dselect-placeholder'
            const classClearBtn = 'dselect-clear'
            const classTogglerClearable = 'dselect-clearable'
            const defaultSearch = false
            const defaultCreatable = false
            const defaultClearable = false
            const defaultMaxHeight = '360px'
            const defaultSize = ''
            const search = attrBool('search') || option.search || defaultSearch
            const creatable = attrBool('creatable') || option.creatable || defaultCreatable
            const clearable = attrBool('clearable') || option.clearable || defaultClearable
            const maxHeight = el.dataset.dselectMaxHeight || option.maxHeight || defaultMaxHeight
            let size = el.dataset.dselectSize || option.size || defaultSize
            size = size !== '' ? ` form-select-${size}` : ''
            const classToggler = `form-select${size}`

            const searchInput = search ? `<input onkeydown="return event.key !== 'Enter'" onkeyup="dselectSearch(event, this, '${classElement}', '${classToggler}', ${creatable})" type="text" class="form-control" placeholder="Search" autofocus>` : ''

            function attrBool(attr) {
                const attribute = `data-dselect-${attr}`
                if (!el.hasAttribute(attribute)) return null

                const value = el.getAttribute(attribute)
                return value.toLowerCase() === 'true'
            }

            function removePrev() {
                if (el.nextElementSibling && el.nextElementSibling.classList && el.nextElementSibling.classList.contains(classElement)) {
                    el.nextElementSibling.remove()
                }
            }

            function isPlaceholder(option) {
                return option.getAttribute('value') === ''
            }

            function selectedTag(options, multiple) {
                if (multiple) {
                    const selectedOptions = Array.from(options).filter(option => option.selected && !isPlaceholder(option))
                    const placeholderOption = Array.from(options).filter(option => isPlaceholder(option))
                    let tag = []
                    if (selectedOptions.length === 0) {
                        const text = placeholderOption.length ? placeholderOption[0].textContent : '&nbsp;'
                        tag.push(`<span class="${classPlaceholder}">${text}</span>`)
                    } else {
                        for (const option of selectedOptions) {
                            tag.push(`
            <div class="${classTag}" data-dselect-value="${option.value}">
              ${option.text}
              <svg onclick="dselectRemoveTag(this, '${classElement}', '${classToggler}')" class="${classTagRemove}" width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
            </div>
          `)
                        }
                    }
                    return tag.join('')
                } else {
                    const selectedOption = options[options.selectedIndex]
                    return isPlaceholder(selectedOption)
                        ? `<span class="${classPlaceholder}">${selectedOption.innerHTML}</span>`
                        : selectedOption.innerHTML
                }
            }

            function selectedText(options) {
                const selectedOption = options[options.selectedIndex]
                return isPlaceholder(selectedOption) ? '' : selectedOption.textContent
            }

            function itemTags(options) {
                let items = []
                for (const option of options) {
                    if (option.tagName === 'OPTGROUP') {
                        items.push(`<h6 class="dropdown-header">${option.getAttribute('label')}</h6>`)
                    } else {
                        const hidden = isPlaceholder(option) ? ' hidden' : ''
                        const active = option.selected ? ' active' : ''
                        const disabled = el.multiple && option.selected ? ' disabled' : ''
                        const value = option.value
                        const text = option.textContent
                        items.push(`<button${hidden} class="dropdown-item${active}" data-dselect-value="${value}" type="button" onclick="dselectUpdate(this, '${classElement}', '${classToggler}')"${disabled}>${text}</button>`)
                    }
                }
                items = items.join('')
                return items
            }

            function createDom() {
                const autoclose = el.multiple ? ' data-bs-auto-close="outside"' : ''
                const additionalClass = Array.from(el.classList).filter(className => {
                    return className !== 'form-select'
                        && className !== 'form-select-sm'
                        && className !== 'form-select-lg'
                }).join(' ')
                const clearBtn = clearable && !el.multiple ? `
    <button type="button" class="btn ${classClearBtn}" title="Clear selection" onclick="dselectClear(this, '${classElement}')">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" fill="none">
        <path d="M13 1L0.999999 13" stroke-width="2" stroke="currentColor"></path>
        <path d="M1 1L13 13" stroke-width="2" stroke="currentColor"></path>
      </svg>
    </button>
    ` : ''
                const template = `
    <div class="dropdown ${classElement} ${additionalClass}">
      <button class="${classToggler} ${!el.multiple && clearable ? classTogglerClearable : ''}" data-dselect-text="${!el.multiple && selectedText(el.options)}" type="button" data-bs-toggle="dropdown" aria-expanded="false"${autoclose}>
        ${selectedTag(el.options, el.multiple)}
      </button>
      <div class="dropdown-menu">
        <div class="d-flex flex-column">
          ${searchInput}
          <div class="dselect-items" style="max-height:${maxHeight};overflow:auto">
            ${itemTags(el.querySelectorAll('*'))}
          </div>
          <div class="${classNoResults} d-none">No results found</div>
        </div>
      </div>
      ${clearBtn}
    </div>
    `
                removePrev()
                el.insertAdjacentHTML('afterend', template) // insert template after element
            }
            createDom()

            function updateDom() {
                const dropdown = el.nextElementSibling
                const toggler = dropdown.getElementsByClassName(classToggler)[0]
                const dSelectItems = dropdown.getElementsByClassName('dselect-items')[0]
                toggler.innerHTML = selectedTag(el.options, el.multiple)
                dSelectItems.innerHTML = itemTags(el.querySelectorAll('*'))
                if (!el.multiple) {
                    toggler.dataset.dselectText = selectedText(el.options)
                }
            }

            el.addEventListener('change', updateDom)
        }
    </script>
@endsection
