/**
 * NovaTube front-end interactions.
 */
( function () {
	'use strict';

	const data = window.novatubeData || {};
	const i18n = data.i18n || {};
	const header = document.querySelector( '.site-header' );
	const navToggle = document.querySelector( '.nav-toggle' );
	const toast = document.getElementById( 'novatube-toast' );
	let toastTimer = null;

	/**
	 * Show a toast message.
	 *
	 * @param {string} message Message.
	 */
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

	/* -----------------------------------------------------------------
	 * Mobile navigation
	 * -------------------------------------------------------------- */
	if ( header && navToggle ) {
		const closeNav = function () {
			header.classList.remove( 'nav-open' );
			navToggle.setAttribute( 'aria-expanded', 'false' );
			navToggle.setAttribute( 'aria-label', 'Open menu' );
		};

		navToggle.addEventListener( 'click', function () {
			const isOpen = header.classList.toggle( 'nav-open' );
			navToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			navToggle.setAttribute( 'aria-label', isOpen ? 'Close menu' : 'Open menu' );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && header.classList.contains( 'nav-open' ) ) {
				closeNav();
				navToggle.focus();
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( header.classList.contains( 'nav-open' ) && ! header.contains( event.target ) ) {
				closeNav();
			}
		} );

		window.addEventListener( 'resize', function () {
			if ( window.innerWidth > 900 ) {
				closeNav();
			}
		} );
	}

	// Mobile submenu toggles.
	const parentLinks = document.querySelectorAll( '.nav-list .menu-item-has-children > a' );

	parentLinks.forEach( function ( link ) {
		link.addEventListener( 'click', function ( event ) {
			if ( window.innerWidth > 900 ) {
				return;
			}

			event.preventDefault();
			const item = link.parentElement;
			const isOpen = item.classList.toggle( 'submenu-open' );
			link.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );
	} );

	if ( window.innerWidth <= 900 ) {
		parentLinks.forEach( function ( link ) {
			link.setAttribute( 'aria-expanded', 'false' );
		} );
	}

	/* -----------------------------------------------------------------
	 * Sticky header shadow
	 * -------------------------------------------------------------- */
	if ( header ) {
		const updateHeader = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 6 );
		};

		updateHeader();
		window.addEventListener( 'scroll', updateHeader, { passive: true } );
	}

	/* -----------------------------------------------------------------
	 * Back to top
	 * -------------------------------------------------------------- */
	const toTop = document.querySelector( '.to-top' );

	if ( toTop ) {
		const updateTopButton = function () {
			toTop.classList.toggle( 'is-visible', window.scrollY > 650 );
		};

		updateTopButton();
		window.addEventListener( 'scroll', updateTopButton, { passive: true } );

		toTop.addEventListener( 'click', function () {
			window.scrollTo( {
				top: 0,
				behavior: 'smooth',
			} );
		} );
	}

	/* -----------------------------------------------------------------
	 * Age gate
	 * -------------------------------------------------------------- */
	const ageGate = document.getElementById( 'novatube-age-gate' );

	if ( ageGate && ! document.documentElement.classList.contains( 'novatube-age-verified' ) ) {
		const dialog = ageGate.querySelector( '.age-gate__dialog' );
		const enterButton = ageGate.querySelector( '[data-age-enter]' );
		const focusable = ageGate.querySelectorAll( 'a[href], button:not([disabled])' );
		let lastFocused = document.activeElement;

		document.body.classList.add( 'novatube-locked' );

		// Make the rest of the page inert while the gate is open.
		[ '.site-header', '#main', '#colophon', '.category-chips', '.to-top' ].forEach( function ( selector ) {
			document.querySelectorAll( selector ).forEach( function ( element ) {
				element.setAttribute( 'inert', '' );
			} );
		} );

		window.setTimeout( function () {
			if ( enterButton ) {
				enterButton.focus();
			} else if ( dialog ) {
				dialog.focus();
			}
		}, 60 );

		const trapFocus = function ( event ) {
			if ( 'Tab' !== event.key || ! focusable.length ) {
				return;
			}

			const first = focusable[ 0 ];
			const last = focusable[ focusable.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		};

		document.addEventListener( 'keydown', trapFocus );

		const enterSite = function () {
			try {
				window.localStorage.setItem( 'novatube_age_verified', '1' );
			} catch ( e ) {}

			document.documentElement.classList.add( 'novatube-age-verified' );
			document.body.classList.remove( 'novatube-locked' );

			[ '.site-header', '#main', '#colophon', '.category-chips', '.to-top' ].forEach( function ( selector ) {
				document.querySelectorAll( selector ).forEach( function ( element ) {
					element.removeAttribute( 'inert' );
				} );
			} );

			document.removeEventListener( 'keydown', trapFocus );
			ageGate.setAttribute( 'hidden', 'hidden' );

			if ( lastFocused && lastFocused.focus ) {
				lastFocused.focus();
			} else {
				document.querySelector( '#main' )?.focus();
			}
		};

		if ( enterButton ) {
			enterButton.addEventListener( 'click', enterSite );
		}
	}

	/* -----------------------------------------------------------------
	 * Load more
	 * -------------------------------------------------------------- */
	const loadMoreButton = document.querySelector( '.js-load-more' );

	if ( loadMoreButton && data.ajaxUrl ) {
		const originalLabel = i18n.loadMore || 'Load more videos';
		const label = loadMoreButton.querySelector( '.js-load-label' );

		loadMoreButton.addEventListener( 'click', async function () {
			if ( loadMoreButton.classList.contains( 'is-loading' ) ) {
				return;
			}

			const currentPage = parseInt( loadMoreButton.dataset.page, 10 ) || 1;
			const nextPage = currentPage + 1;
			const grid = document.getElementById( 'latest-videos' );

			if ( ! grid ) {
				return;
			}

			loadMoreButton.classList.add( 'is-loading' );
			loadMoreButton.setAttribute( 'aria-busy', 'true' );

			if ( label ) {
				label.textContent = i18n.loading || 'Loading…';
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
					throw new Error( 'Request failed' );
				}

				if ( json.data.html ) {
					grid.insertAdjacentHTML( 'beforeend', json.data.html );
				}

				loadMoreButton.dataset.page = String( nextPage );
				loadMoreButton.dataset.max = String( json.data.max || nextPage );

				if ( nextPage >= ( json.data.max || nextPage ) || ! json.data.html ) {
					loadMoreButton.parentElement.remove();
				} else if ( label ) {
					label.textContent = originalLabel;
				}
			} catch ( error ) {
				if ( label ) {
					label.textContent = i18n.error || 'Please try again.';
				}

				window.setTimeout( function () {
					if ( label ) {
						label.textContent = originalLabel;
					}
				}, 2600 );
			} finally {
				loadMoreButton.classList.remove( 'is-loading' );
				loadMoreButton.removeAttribute( 'aria-busy' );
			}
		} );
	}

	/* -----------------------------------------------------------------
	 * Video rating
	 * -------------------------------------------------------------- */
	document.querySelectorAll( '.rating-widget' ).forEach( function ( widget ) {
		const videoId = widget.dataset.videoId;
		const stars = Array.from( widget.querySelectorAll( '.rating-star' ) );
		const valueEl = widget.querySelector( '.js-rating-value' );
		const countEl = widget.querySelector( '.js-rating-count' );
		const labelEl = widget.querySelector( '.js-rating-label' );
		const hintEl = widget.querySelector( '.rating-widget__hint' );
		let rated = false;

		if ( ! videoId || ! stars.length ) {
			return;
		}

		const clearPreview = function () {
			stars.forEach( function ( star ) {
				star.classList.remove( 'is-preview' );
			} );
		};

		const fillStars = function ( value ) {
			stars.forEach( function ( star ) {
				star.classList.toggle( 'is-filled', Number( star.dataset.value ) <= Math.round( value ) );
			} );
		};

		stars.forEach( function ( star ) {
			star.addEventListener( 'mouseenter', function () {
				if ( rated ) {
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
				if ( rated ) {
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
						throw new Error( json && json.data && json.data.message ? json.data.message : i18n.rateError );
					}

					rated = true;
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
						hintEl.textContent = json.data.message || i18n.rateThanks;
					}

					widget.classList.add( 'is-thanked' );
					showToast( json.data.message || i18n.rateThanks );
				} catch ( error ) {
					if ( hintEl ) {
						hintEl.textContent = error.message || i18n.rateError;
					}

					showToast( error.message || i18n.rateError );

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
	const copyText = async function ( text ) {
		if ( navigator.clipboard && window.isSecureContext ) {
			await navigator.clipboard.writeText( text );
			return;
		}

		const textarea = document.createElement( 'textarea' );
		textarea.value = text;
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

	document.querySelectorAll( '.js-copy-link' ).forEach( function ( button ) {
		button.addEventListener( 'click', async function () {
			const url = button.dataset.url || window.location.href;

			try {
				await copyText( url );
				showToast( i18n.copied || 'Link copied' );
				button.classList.add( 'is-copied' );
				window.setTimeout( function () {
					button.classList.remove( 'is-copied' );
				}, 2000 );
			} catch ( error ) {
				showToast( i18n.copyError || 'Could not copy the link.' );
			}
		} );
	} );

	/* -----------------------------------------------------------------
	 * Collapsible video description
	 * -------------------------------------------------------------- */
	document.querySelectorAll( '.js-video-description' ).forEach( function ( content ) {
		const section = content.closest( '.video-description' );
		const button = section ? section.querySelector( '.js-read-more' ) : null;

		if ( ! button || content.scrollHeight < 210 ) {
			return;
		}

		content.classList.add( 'is-collapsed' );
		button.hidden = false;

		button.addEventListener( 'click', function () {
			const isOpen = content.classList.toggle( 'is-collapsed' ) === false;
			button.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );
	} );
} )();
