/**
 * NovaTube front-end interactions.
 *
 * No dependencies: dialogs, live search, saved videos, ratings, lightbox,
 * load more, theme switching, age gate, and small quality-of-life helpers.
 */
( function () {
	'use strict';

	const data = window.novatubeData || {};
	const i18n = data.i18n || {};
	const doc = document.documentElement;
	const toast = document.getElementById( 'novatube-toast' );
	const header = document.querySelector( '.nt-header' );
	const savedKey = 'novatube_saved_v1';
	let toastTimer = null;
	let viewCounted = false;

	const text = {
		loading: i18n.loading || 'Loading…',
		loadMore: i18n.loadMore || 'Load more videos',
		noMore: i18n.noMore || 'You’ve reached the end.',
		error: i18n.error || 'Something went wrong. Please try again.',
		copied: i18n.copied || 'Link copied to clipboard',
		copyError: i18n.copyError || 'Could not copy the link. Please copy it manually.',
		rateError: i18n.rateError || 'Your rating could not be saved.',
		rateThanks: i18n.rateThanks || 'Thanks for rating!',
		saved: i18n.saved || 'Saved to your list',
		removed: i18n.removed || 'Removed from your list',
		savedEmpty: i18n.savedEmpty || 'No saved videos yet. Tap the bookmark on any video to keep it here.',
		searching: i18n.searching || 'Searching…',
		noResults: i18n.noResults || 'No matches. Try another keyword.',
		save: i18n.save || 'Save',
		savedLabel: i18n.savedLabel || 'Saved',
		remove: i18n.remove || 'Remove',
	};

	/* -----------------------------------------------------------------
	 * Small helpers
	 * -------------------------------------------------------------- */
	function showToast( message ) {
		if ( ! toast || ! message ) {
			return;
		}

		toast.textContent = message;
		toast.classList.add( 'is-visible' );

		window.clearTimeout( toastTimer );
		toastTimer = window.setTimeout( function () {
			toast.classList.remove( 'is-visible' );
		}, 2600 );
	}

	function isTyping( element ) {
		if ( ! element ) {
			return false;
		}

		const tag = ( element.tagName || '' ).toLowerCase();

		return 'input' === tag || 'textarea' === tag || 'select' === tag || element.isContentEditable;
	}

	function updateScrollLock() {
		const open = document.querySelector( 'dialog[open]' );
		document.body.classList.toggle( 'nt-no-scroll', Boolean( open ) );
	}

	function openDialog( dialog ) {
		if ( ! dialog || ! dialog.showModal ) {
			return;
		}

		const gate = document.getElementById( 'novatube-age-gate' );

		if ( gate && gate.open && dialog !== gate ) {
			return;
		}

		document.querySelectorAll( 'dialog[open]' ).forEach( function ( other ) {
			if ( other !== dialog && other !== gate ) {
				other.close();
			}
		} );

		if ( ! dialog.open ) {
			dialog.showModal();
		}

		updateScrollLock();
	}

	function closeDialog( dialog ) {
		if ( dialog && dialog.open ) {
			dialog.close();
		}

		updateScrollLock();
	}

	document.querySelectorAll( 'dialog' ).forEach( function ( dialog ) {
		dialog.addEventListener( 'close', updateScrollLock );
		dialog.addEventListener( 'click', function ( event ) {
			if ( event.target === dialog ) {
				closeDialog( dialog );
			}
		} );
	} );

	document.addEventListener( 'click', function ( event ) {
		const closeButton = event.target.closest( '.js-dialog-close' );

		if ( closeButton ) {
			event.preventDefault();
			closeDialog( closeButton.closest( 'dialog' ) );
		}
	} );

	/* -----------------------------------------------------------------
	 * Sticky header and back to top
	 * -------------------------------------------------------------- */
	if ( header ) {
		const updateHeader = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 6 );
		};

		updateHeader();
		window.addEventListener( 'scroll', updateHeader, { passive: true } );
	}

	const toTop = document.querySelector( '.nt-to-top' );

	if ( toTop ) {
		const updateTopButton = function () {
			toTop.classList.toggle( 'is-visible', window.scrollY > 650 );
		};

		updateTopButton();
		window.addEventListener( 'scroll', updateTopButton, { passive: true } );

		toTop.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	/* -----------------------------------------------------------------
	 * Drawer
	 * -------------------------------------------------------------- */
	const drawer = document.getElementById( 'nt-drawer' );

	document.querySelectorAll( '.js-drawer-open' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			openDialog( drawer );
		} );
	} );

	/* -----------------------------------------------------------------
	 * Theme mode
	 * -------------------------------------------------------------- */
	function setTheme( mode ) {
		doc.setAttribute( 'data-theme', 'light' === mode ? 'light' : 'dark' );

		try {
			window.localStorage.setItem( 'novatube_theme', 'light' === mode ? 'light' : 'dark' );
		} catch ( e ) {}
	}

	document.querySelectorAll( '.js-theme-toggle' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			setTheme( 'light' === doc.getAttribute( 'data-theme' ) ? 'dark' : 'light' );
		} );
	} );

	/* -----------------------------------------------------------------
	 * Saved videos (local to this browser)
	 * -------------------------------------------------------------- */
	function readSaved() {
		try {
			const value = JSON.parse( window.localStorage.getItem( savedKey ) || '[]' );

			return Array.isArray( value ) ? value.filter( function ( item ) {
				return item && item.id;
			} ) : [];
		} catch ( e ) {
			return [];
		}
	}

	function writeSaved( items ) {
		try {
			window.localStorage.setItem( savedKey, JSON.stringify( items ) );
		} catch ( e ) {}
	}

	let savedItems = readSaved();

	function isSaved( id ) {
		return savedItems.some( function ( item ) {
			return String( item.id ) === String( id );
		} );
	}

	function syncSavedUI() {
		document.querySelectorAll( '.js-save' ).forEach( function ( button ) {
			const active = isSaved( button.dataset.id );
			button.classList.toggle( 'is-saved', active );
			button.setAttribute( 'aria-pressed', active ? 'true' : 'false' );

			const label = button.querySelector( '.js-save-label' );

			if ( label ) {
				label.textContent = active ? text.savedLabel : text.save;
			}
		} );

		document.querySelectorAll( '.js-saved-count' ).forEach( function ( count ) {
			count.textContent = String( savedItems.length );
			count.hidden = ! savedItems.length;
		} );
	}

	function renderSaved() {
		const list = document.querySelector( '[data-saved-list]' );
		const empty = document.querySelector( '[data-saved-empty]' );

		if ( ! list ) {
			return;
		}

		list.textContent = '';

		if ( empty ) {
			empty.hidden = Boolean( savedItems.length );
		}

		savedItems.forEach( function ( item ) {
			const row = document.createElement( 'div' );
			row.className = 'nt-saved__item';
			row.dataset.id = item.id;

			const thumbLink = document.createElement( 'a' );
			thumbLink.className = 'nt-saved__thumb';
			thumbLink.href = item.url || '#';
			thumbLink.tabIndex = -1;
			thumbLink.setAttribute( 'aria-hidden', 'true' );

			if ( item.thumb ) {
				const image = document.createElement( 'img' );
				image.src = item.thumb;
				image.alt = '';
				image.loading = 'lazy';
				thumbLink.appendChild( image );
			}

			const body = document.createElement( 'div' );
			body.className = 'nt-saved__body';

			const title = document.createElement( 'a' );
			title.className = 'nt-saved__title';
			title.href = item.url || '#';
			title.textContent = item.title || '';

			const meta = document.createElement( 'span' );
			meta.className = 'nt-saved__meta';
			meta.textContent = item.meta || '';

			body.appendChild( title );
			body.appendChild( meta );

			const remove = document.createElement( 'button' );
			remove.type = 'button';
			remove.className = 'nt-saved__remove';
			remove.dataset.remove = item.id;
			remove.setAttribute( 'aria-label', text.remove + ' ' + ( item.title || '' ) );
			remove.title = text.remove;
			remove.textContent = '×';

			row.appendChild( thumbLink );
			row.appendChild( body );
			row.appendChild( remove );
			list.appendChild( row );
		} );
	}

	function toggleSaved( button ) {
		const id = button.dataset.id;

		if ( ! id ) {
			return;
		}

		if ( isSaved( id ) ) {
			savedItems = savedItems.filter( function ( item ) {
				return String( item.id ) !== String( id );
			} );
			showToast( text.removed );
		} else {
			savedItems.unshift( {
				id: String( id ),
				title: button.dataset.title || '',
				url: button.dataset.url || window.location.href,
				thumb: button.dataset.thumb || '',
				meta: button.dataset.meta || '',
			} );
			showToast( text.saved );
		}

		writeSaved( savedItems );
		syncSavedUI();
		renderSaved();
	}

	document.addEventListener( 'click', function ( event ) {
		const button = event.target.closest( '.js-save' );

		if ( button ) {
			event.preventDefault();
			toggleSaved( button );
		}

		const remove = event.target.closest( '[data-remove]' );

		if ( remove ) {
			event.preventDefault();
			savedItems = savedItems.filter( function ( item ) {
				return String( item.id ) !== String( remove.dataset.remove );
			} );
			writeSaved( savedItems );
			syncSavedUI();
			renderSaved();
			showToast( text.removed );
		}
	} );

	document.querySelectorAll( '.js-saved-open' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			renderSaved();
			openDialog( document.getElementById( 'nt-saved' ) );
		} );
	} );

	syncSavedUI();
	renderSaved();

	/* -----------------------------------------------------------------
	 * Live search
	 * -------------------------------------------------------------- */
	const searchDialog = document.getElementById( 'nt-search' );
	const searchInput = searchDialog ? searchDialog.querySelector( '[data-search-input]' ) : null;
	const searchResults = searchDialog ? searchDialog.querySelector( '[data-search-results]' ) : null;
	let searchTimer = null;
	let searchController = null;
	let activeResult = -1;

	function setSearchMessage( message ) {
		if ( searchResults ) {
			searchResults.innerHTML = '<div class="nt-search__empty"></div>';
			searchResults.firstElementChild.textContent = message;
		}
	}

	function updateActiveResult( items, index ) {
		items.forEach( function ( item, position ) {
			item.classList.toggle( 'is-active', position === index );
		} );

		if ( items[ index ] ) {
			items[ index ].scrollIntoView( { block: 'nearest' } );
		}
	}

	async function runSearch( term ) {
		if ( ! data.ajaxUrl || ! data.searchNonce || ! searchResults ) {
			return;
		}

		if ( searchController ) {
			searchController.abort();
		}

		searchController = new AbortController();
		activeResult = -1;
		setSearchMessage( text.searching );

		try {
			const params = new URLSearchParams( {
				action: 'novatube_search',
				nonce: data.searchNonce,
				term: term,
			} );

			const response = await fetch( data.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
				body: params.toString(),
				signal: searchController.signal,
			} );

			const json = await response.json();

			if ( ! json || ! json.success ) {
				throw new Error( text.error );
			}

			searchResults.innerHTML = json.data.html || '';
		} catch ( error ) {
			if ( 'AbortError' !== error.name ) {
				setSearchMessage( text.error );
			}
		}
	}

	function openSearch() {
		openDialog( searchDialog );

		window.setTimeout( function () {
			if ( searchInput ) {
				searchInput.focus();
				searchInput.select();
			}
		}, 40 );
	}

	document.querySelectorAll( '.js-search-open' ).forEach( function ( button ) {
		button.addEventListener( 'click', openSearch );
	} );

	if ( searchInput ) {
		searchInput.addEventListener( 'input', function () {
			const term = searchInput.value.trim();

			window.clearTimeout( searchTimer );

			if ( term.length < 2 ) {
				setSearchMessage( i18n.searchHint || 'Search videos, models, categories…' );
				return;
			}

			searchTimer = window.setTimeout( function () {
				runSearch( term );
			}, 220 );
		} );

		searchInput.addEventListener( 'keydown', function ( event ) {
			const items = searchResults ? Array.from( searchResults.querySelectorAll( '.nt-search__item' ) ) : [];

			if ( 'ArrowDown' === event.key && items.length ) {
				event.preventDefault();
				activeResult = ( activeResult + 1 ) % items.length;
				updateActiveResult( items, activeResult );
			} else if ( 'ArrowUp' === event.key && items.length ) {
				event.preventDefault();
				activeResult = activeResult <= 0 ? items.length - 1 : activeResult - 1;
				updateActiveResult( items, activeResult );
			} else if ( 'Enter' === event.key && activeResult >= 0 && items[ activeResult ] ) {
				event.preventDefault();
				window.location.href = items[ activeResult ].href;
			}
		} );

		searchDialog.querySelector( 'form' ).addEventListener( 'submit', function ( event ) {
			if ( ! searchInput.value.trim() ) {
				event.preventDefault();
				searchInput.focus();
			}
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		const key = ( event.key || '' ).toLowerCase();
		const command = event.metaKey || event.ctrlKey;

		if ( command && 'k' === key ) {
			event.preventDefault();
			openSearch();
		} else if ( '/' === key && ! isTyping( document.activeElement ) && ! document.querySelector( 'dialog[open]' ) ) {
			event.preventDefault();
			openSearch();
		}
	} );

	/* -----------------------------------------------------------------
	 * Load more
	 * -------------------------------------------------------------- */
	document.querySelectorAll( '.js-load-more' ).forEach( function ( loadMoreButton ) {
		const label = loadMoreButton.querySelector( '.js-load-label' );
		const spinner = loadMoreButton.querySelector( '.nt-spinner' );
		const grid = document.getElementById( 'latest-videos' );

		if ( ! grid || ! data.ajaxUrl ) {
			return;
		}

		loadMoreButton.addEventListener( 'click', async function () {
			if ( loadMoreButton.classList.contains( 'is-loading' ) ) {
				return;
			}

			const currentPage = parseInt( loadMoreButton.dataset.page, 10 ) || 1;
			const nextPage = currentPage + 1;

			loadMoreButton.classList.add( 'is-loading' );
			loadMoreButton.setAttribute( 'aria-busy', 'true' );

			if ( label ) {
				label.textContent = text.loading;
			}

			if ( spinner ) {
				spinner.hidden = false;
			}

			try {
				const params = new URLSearchParams( {
					action: 'novatube_load_more',
					nonce: data.loadNonce,
					page: String( nextPage ),
				} );

				const response = await fetch( data.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					},
					body: params.toString(),
				} );

				const json = await response.json();

				if ( ! json || ! json.success ) {
					throw new Error( text.error );
				}

				if ( json.data.html ) {
					grid.insertAdjacentHTML( 'beforeend', json.data.html );
					syncSavedUI();
				}

				loadMoreButton.dataset.page = String( nextPage );
				loadMoreButton.dataset.max = String( json.data.max || nextPage );

				if ( nextPage >= ( json.data.max || nextPage ) || ! json.data.html ) {
					const wrap = loadMoreButton.closest( '.nt-load-more-wrap' );

					if ( wrap ) {
						wrap.remove();
					}
				} else if ( label ) {
					label.textContent = text.loadMore;
				}
			} catch ( error ) {
				if ( label ) {
					label.textContent = text.error;
				}

				window.setTimeout( function () {
					if ( label ) {
						label.textContent = text.loadMore;
					}
				}, 2600 );
			} finally {
				loadMoreButton.classList.remove( 'is-loading' );
				loadMoreButton.removeAttribute( 'aria-busy' );

				if ( spinner ) {
					spinner.hidden = true;
				}
			}
		} );
	} );

	/* -----------------------------------------------------------------
	 * Rating
	 * -------------------------------------------------------------- */
	document.querySelectorAll( '.nt-rating' ).forEach( function ( widget ) {
		const videoId = widget.dataset.videoId;
		const stars = Array.from( widget.querySelectorAll( '.nt-star' ) );
		const valueEl = widget.querySelector( '.js-rating-value' );
		const countEl = widget.querySelector( '.js-rating-count' );
		const labelEl = widget.querySelector( '.js-rating-label' );
		const hintEl = widget.querySelector( '.js-rating-hint' );

		if ( ! videoId || ! stars.length ) {
			return;
		}

		const fillStars = function ( value ) {
			stars.forEach( function ( star ) {
				star.classList.toggle( 'is-filled', Number( star.dataset.value ) <= Math.round( value ) );
			} );
		};

		const clearPreview = function () {
			stars.forEach( function ( star ) {
				star.classList.remove( 'is-preview' );
			} );
		};

		stars.forEach( function ( star ) {
			star.addEventListener( 'mouseenter', function () {
				if ( widget.classList.contains( 'is-thanked' ) ) {
					return;
				}

				clearPreview();
				const value = Number( star.dataset.value );

				stars.forEach( function ( item ) {
					item.classList.toggle( 'is-preview', Number( item.dataset.value ) <= value );
				} );
			} );

			star.addEventListener( 'mouseleave', clearPreview );

			star.addEventListener( 'click', async function () {
				if ( widget.classList.contains( 'is-thanked' ) || widget.getAttribute( 'aria-busy' ) ) {
					return;
				}

				const rating = Number( star.dataset.value );

				stars.forEach( function ( item ) {
					item.disabled = true;
				} );

				widget.setAttribute( 'aria-busy', 'true' );

				try {
					const params = new URLSearchParams( {
						action: 'novatube_rate_video',
						nonce: data.rateNonce,
						post_id: videoId,
						rating: String( rating ),
					} );

					const response = await fetch( data.ajaxUrl, {
						method: 'POST',
						credentials: 'same-origin',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
						},
						body: params.toString(),
					} );

					const json = await response.json();

					if ( ! json || ! json.success ) {
						throw new Error( json && json.data && json.data.message ? json.data.message : text.rateError );
					}

					clearPreview();

					if ( valueEl ) {
						valueEl.textContent = json.data.rating;
					}

					if ( countEl ) {
						countEl.textContent = json.data.count;
					}

					if ( labelEl ) {
						labelEl.textContent = json.data.label;
					}

					fillStars( parseFloat( json.data.rating ) || rating );

					if ( hintEl ) {
						hintEl.textContent = json.data.message || text.rateThanks;
					}

					widget.classList.add( 'is-thanked' );
					showToast( json.data.message || text.rateThanks );
				} catch ( error ) {
					if ( hintEl ) {
						hintEl.textContent = error.message || text.rateError;
					}

					showToast( error.message || text.rateError );
					stars.forEach( function ( item ) {
						item.disabled = false;
					} );
				} finally {
					widget.removeAttribute( 'aria-busy' );
				}
			} );
		} );
	} );

	/* -----------------------------------------------------------------
	 * Copy link
	 * -------------------------------------------------------------- */
	const copyText = async function ( value ) {
		if ( navigator.clipboard && window.isSecureContext ) {
			await navigator.clipboard.writeText( value );
			return;
		}

		const textarea = document.createElement( 'textarea' );
		textarea.value = value;
		textarea.setAttribute( 'readonly', '' );
		textarea.style.position = 'fixed';
		textarea.style.opacity = '0';
		document.body.appendChild( textarea );
		textarea.select();

		const copied = document.execCommand( 'copy' );
		document.body.removeChild( textarea );

		if ( ! copied ) {
			throw new Error( 'copy-failed' );
		}
	};

	document.addEventListener( 'click', async function ( event ) {
		const button = event.target.closest( '.js-copy-link' );

		if ( ! button ) {
			return;
		}

		event.preventDefault();
		const url = button.dataset.url || window.location.href;

		try {
			await copyText( url );
			showToast( text.copied );
			button.classList.add( 'is-copied' );
			window.setTimeout( function () {
				button.classList.remove( 'is-copied' );
			}, 2000 );
		} catch ( error ) {
			showToast( text.copyError );
		}
	} );

	/* -----------------------------------------------------------------
	 * Collapsible description
	 * -------------------------------------------------------------- */
	document.querySelectorAll( '.js-video-description' ).forEach( function ( content ) {
		const section = content.closest( '.nt-description' );
		const button = section ? section.querySelector( '.js-read-more' ) : null;

		if ( ! button ) {
			return;
		}

		if ( content.scrollHeight <= 210 ) {
			section.classList.add( 'is-open' );
			button.hidden = true;
			return;
		}

		button.hidden = false;

		const moreLabel = button.querySelector( '.js-readmore-more' );
		const lessLabel = button.querySelector( '.js-readmore-less' );

		button.addEventListener( 'click', function () {
			const open = section.classList.toggle( 'is-open' );
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

			if ( moreLabel ) {
				moreLabel.hidden = open;
			}

			if ( lessLabel ) {
				lessLabel.hidden = ! open;
			}
		} );
	} );

	/* -----------------------------------------------------------------
	 * Gallery lightbox
	 * -------------------------------------------------------------- */
	const lightbox = document.getElementById( 'nt-lightbox' );
	let lightboxItems = [];
	let lightboxIndex = 0;

	function showLightboxItem( index ) {
		if ( ! lightbox || ! lightboxItems.length ) {
			return;
		}

		lightboxIndex = ( index + lightboxItems.length ) % lightboxItems.length;

		const item = lightboxItems[ lightboxIndex ];
		const image = lightbox.querySelector( '[data-lightbox-image]' );
		const count = lightbox.querySelector( '[data-lightbox-count]' );
		const prev = lightbox.querySelector( '[data-lightbox-prev]' );
		const next = lightbox.querySelector( '[data-lightbox-next]' );

		if ( image ) {
			image.src = item.dataset.full;
			image.alt = item.querySelector( 'img' ) ? item.querySelector( 'img' ).alt : '';
		}

		if ( count ) {
			count.textContent = ( lightboxIndex + 1 ) + ' / ' + lightboxItems.length;
		}

		[ prev, next ].forEach( function ( button ) {
			if ( button ) {
				button.hidden = lightboxItems.length < 2;
			}
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		const trigger = event.target.closest( '.js-lightbox' );

		if ( trigger && lightbox ) {
			event.preventDefault();
			const gallery = trigger.closest( '.nt-gallery' );
			lightboxItems = Array.from( ( gallery || document ).querySelectorAll( '.js-lightbox' ) );
			lightboxIndex = Math.max( 0, lightboxItems.indexOf( trigger ) );
			showLightboxItem( lightboxIndex );
			openDialog( lightbox );
		}

		const prev = event.target.closest( '[data-lightbox-prev]' );
		const next = event.target.closest( '[data-lightbox-next]' );

		if ( prev ) {
			event.preventDefault();
			showLightboxItem( lightboxIndex - 1 );
		}

		if ( next ) {
			event.preventDefault();
			showLightboxItem( lightboxIndex + 1 );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( ! lightbox || ! lightbox.open ) {
			return;
		}

		if ( 'ArrowLeft' === event.key ) {
			event.preventDefault();
			showLightboxItem( lightboxIndex - 1 );
		} else if ( 'ArrowRight' === event.key ) {
			event.preventDefault();
			showLightboxItem( lightboxIndex + 1 );
		}
	} );

	/* -----------------------------------------------------------------
	 * Fullscreen
	 * -------------------------------------------------------------- */
	document.addEventListener( 'click', function ( event ) {
		const button = event.target.closest( '.js-fullscreen' );

		if ( ! button ) {
			return;
		}

		const target = document.querySelector( button.dataset.target || '.nt-player__ratio' );
		const video = target ? target.querySelector( 'video' ) : null;
		const element = video || target;

		if ( element && element.requestFullscreen ) {
			element.requestFullscreen().catch( function () {} );
		}
	} );

	/* -----------------------------------------------------------------
	 * Age gate
	 * -------------------------------------------------------------- */
	const ageGate = document.getElementById( 'novatube-age-gate' );
	let ageVerified = doc.classList.contains( 'novatube-age-verified' );

	if ( ageGate && data.ageGate && ! ageVerified ) {
		ageGate.addEventListener( 'cancel', function ( event ) {
			event.preventDefault();
		} );

		window.setTimeout( function () {
			openDialog( ageGate );
			const enter = ageGate.querySelector( '[data-age-enter]' );

			if ( enter ) {
				enter.focus();
			}
		}, 60 );
	}

	function markAgeVerified() {
		ageVerified = true;
		doc.classList.add( 'novatube-age-verified' );

		try {
			window.localStorage.setItem( 'novatube_age_verified', '1' );
		} catch ( e ) {}

		if ( ageGate && ageGate.open ) {
			ageGate.close();
		}

		countView();
	}

	if ( ageGate ) {
		const enter = ageGate.querySelector( '[data-age-enter]' );

		if ( enter ) {
			enter.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				markAgeVerified();
			} );
		}
	}

	/* -----------------------------------------------------------------
	 * View count
	 * -------------------------------------------------------------- */
	async function countView() {
		if ( viewCounted || ! data.postId || ! data.ajaxUrl || ! data.viewNonce ) {
			return;
		}

		if ( data.ageGate && ! ageVerified ) {
			return;
		}

		viewCounted = true;

		try {
			const params = new URLSearchParams( {
				action: 'novatube_count_view',
				nonce: data.viewNonce,
				post_id: String( data.postId ),
			} );

			const response = await fetch( data.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
				body: params.toString(),
			} );

			const json = await response.json();

			if ( json && json.success && json.data && json.data.views ) {
				document.querySelectorAll( '.js-view-count' ).forEach( function ( element ) {
					element.textContent = json.data.views;
				} );
			}
		} catch ( error ) {
			viewCounted = false;
		}
	}

	countView();

	/* -----------------------------------------------------------------
	 * Close open dropdowns when clicking elsewhere
	 * -------------------------------------------------------------- */
	document.addEventListener( 'click', function ( event ) {
		if ( event.target.closest( 'details' ) ) {
			return;
		}

		document.querySelectorAll( 'details.nt-menu[open], details.nt-nav__item[open]' ).forEach( function ( details ) {
			details.open = false;
		} );
	} );
} )();
