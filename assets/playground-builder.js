( function() {
	const app = document.querySelector( '[data-playground-builder]' );

	if ( ! app ) {
		return;
	}

	const config = JSON.parse( app.querySelector( '#playground-builder-config' ).textContent );
	const strings = config.strings;
	const state = {
		tab: config.defaultTab,
		query: '',
		searchedQuery: '',
		rawResults: [],
		loading: true,
		error: '',
		selectedPlugins: [],
		selectedThemes: [],
		language: config.defaultLanguage,
		wpVersion: config.defaultWpVersion,
		phpVersion: config.defaultPhpVersion,
		landingPage: config.defaultLandingPage,
		customLandingPage: '/',
		linkMode: 'query',
		showJson: false,
		copied: false,
	};

	const els = {
		tabs: app.querySelectorAll( '[data-tab]' ),
		query: app.querySelector( '[data-query]' ),
		form: app.querySelector( '[data-search-form]' ),
		featuredInfo: app.querySelector( '[data-featured-info]' ),
		results: app.querySelector( '[data-results]' ),
		selected: app.querySelector( '[data-selected]' ),
		selectionSummary: app.querySelector( '[data-selection-summary]' ),
		language: app.querySelector( '[data-language]' ),
		wpVersion: app.querySelector( '[data-wp-version]' ),
		phpVersion: app.querySelector( '[data-php-version]' ),
		landingPage: app.querySelector( '[data-landing-page]' ),
		customLandingPage: app.querySelector( '[data-custom-landing]' ),
		link: app.querySelector( '[data-link]' ),
		open: app.querySelector( '[data-open]' ),
		copy: app.querySelector( '[data-copy]' ),
		buildSummary: app.querySelector( '[data-build-summary]' ),
		json: app.querySelector( '[data-json]' ),
		jsonToggle: app.querySelector( '[data-json-toggle]' ),
	};

	function decodeHtml( value ) {
		const element = document.createElement( 'div' );
		element.innerHTML = value || '';
		return ( element.textContent || '' ).replace( /\s+/g, ' ' ).trim();
	}

	function installs( count ) {
		if ( count === null || count === undefined ) {
			return '';
		}

		if ( count >= 1000000 ) {
			const amount = count / 1000000;
			return strings.activeInstallsMillions.replace( '%s', amount % 1 === 0 ? amount.toFixed( 0 ) : amount.toFixed( 1 ) );
		}

		if ( count >= 1000 ) {
			return strings.activeInstallsThousands.replace( '%s', Math.floor( count / 1000 ) );
		}

		return strings.activeInstalls.replace( '%s', count );
	}

	function pluginUrl( slug ) {
		return slug ? 'https://wordpress.org/plugins/' + encodeURIComponent( slug ) + '/' : '';
	}

	function themeUrl( slug ) {
		return slug ? 'https://wordpress.org/themes/' + encodeURIComponent( slug ) + '/' : '';
	}

	function themePreviewUrl( slug ) {
		return slug ? themeUrl( slug ) + 'preview/' : '';
	}

	function directoryUrl( item ) {
		return item.pluginUrl || item.themeUrl || '';
	}

	function lastUpdated( value ) {
		if ( ! value ) {
			return '';
		}

		return strings.lastUpdated.replace( '%s', String( value ).split( ' ' )[0] );
	}

	function joinMeta( items ) {
		return items.filter( Boolean ).join( ' - ' );
	}

	function resizedImageUrl( value, size ) {
		if ( ! value || value.indexOf( '.svg' ) !== -1 ) {
			return value || '';
		}

		try {
			const url = new URL( value, window.location.href );

			if ( url.protocol !== 'http:' && url.protocol !== 'https:' ) {
				return value;
			}

			const resized = new URL( 'https://i0.wp.com/' + url.hostname + url.pathname );
			resized.searchParams.set( 'resize', size + ',' + size );
			resized.searchParams.set( 'ssl', '1' );

			return resized.toString();
		} catch ( error ) {
			return value;
		}
	}

	function sanitizeFolderName( value ) {
		return ( value || 'custom-package' )
			.toLowerCase()
			.replace( /[^a-z0-9_-]+/g, '-' )
			.replace( /^-+|-+$/g, '' ) || 'custom-package';
	}

	function refType( ref ) {
		return /^[a-f0-9]{40}$/i.test( ref ) ? 'commit' : 'branch';
	}

	function customUrlResult( query, tab ) {
		const value = ( query || '' ).trim();

		if ( ! /^https?:\/\//i.test( value ) ) {
			return null;
		}

		let url;

		try {
			url = new URL( value );
		} catch ( error ) {
			return null;
		}

		const type = tab === 'themes' ? 'theme' : 'plugin';
		const pathSegments = url.pathname.split( '/' ).filter( Boolean ).map( decodeURIComponent );
		const isZip = /\.zip$/i.test( url.pathname );
		const isGitProvider = /(^|\.)github\.com$/i.test( url.hostname ) || /(^|\.)gitlab\.com$/i.test( url.hostname );

		if ( ! isZip && ! isGitProvider ) {
			return null;
		}

		if ( isZip ) {
			const filename = pathSegments[pathSegments.length - 1] || 'custom-package.zip';
			const name = filename.replace( /\.zip$/i, '' );

			return {
				slug: 'custom-url-' + sanitizeFolderName( value ),
				name,
				meta: strings.customZipFile,
				desc: value,
				iconUrl: '',
				type,
				isCustom: true,
				resourceData: {
					resource: 'url',
					url: value,
				},
				targetFolderName: sanitizeFolderName( name ),
			};
		}

		let repoSegments = pathSegments.slice( 0, 2 );
		let ref = '';
		let directory = '';

		if ( /(^|\.)gitlab\.com$/i.test( url.hostname ) ) {
			const markerIndex = pathSegments.indexOf( '-' );

			if ( markerIndex > 0 ) {
				repoSegments = pathSegments.slice( 0, markerIndex );

				if ( pathSegments[markerIndex + 1] === 'tree' && pathSegments[markerIndex + 2] ) {
					ref = pathSegments[markerIndex + 2];
					directory = pathSegments.slice( markerIndex + 3 ).join( '/' );
				}
			}
		} else if ( pathSegments[2] === 'tree' && pathSegments[3] ) {
			ref = pathSegments[3];
			directory = pathSegments.slice( 4 ).join( '/' );
		}

		if ( repoSegments.length < 2 ) {
			return null;
		}

		const repoUrl = url.origin + '/' + repoSegments.join( '/' );
		const repoName = repoSegments[repoSegments.length - 1].replace( /\.git$/i, '' );
		const folderName = directory ? directory.split( '/' ).filter( Boolean ).pop() : repoName;
		const resourceData = {
			resource: 'git:directory',
			url: repoUrl,
		};

		if ( ref ) {
			resourceData.ref = ref;
			resourceData.refType = refType( ref );
		}

		if ( directory ) {
			resourceData.path = directory;
		}

		return {
			slug: 'custom-git-' + sanitizeFolderName( repoUrl + '-' + ( directory || repoName ) + '-' + ( ref || 'head' ) ),
			name: folderName,
			meta: strings.customGitRepository,
			desc: value,
			iconUrl: '',
			type,
			isCustom: true,
			resourceData,
			targetFolderName: sanitizeFolderName( folderName ),
		};
	}

	function normalize( item, tab ) {
		if ( tab === 'plugins' ) {
			const icons = item.icons || {};
			const icon = icons[ '1x' ] || icons.default || icons[ '2x' ] || icons.svg || '';
			return {
				slug: item.slug,
				name: decodeHtml( item.name ),
				meta: joinMeta( [ installs( item.active_installs ), lastUpdated( item.last_updated ) ] ),
				desc: decodeHtml( item.short_description || '' ),
				iconUrl: resizedImageUrl( icon, 96 ),
				pluginUrl: pluginUrl( item.slug ),
				previewLink: item.preview_link || 'https://playground.wordpress.net/?plugin=' + encodeURIComponent( item.slug ),
				previewLabel: item.preview_link ? strings.authorPreview : strings.playgroundPreview,
				previewKind: item.preview_link ? 'author' : 'playground',
				type: 'plugin',
			};
		}

		let screenshot = item.screenshot_url || '';
		let author = '';

		if ( screenshot.indexOf( '//' ) === 0 ) {
			screenshot = 'https:' + screenshot;
		}

		if ( item.author ) {
			author = typeof item.author === 'object' ? item.author.display_name || item.author.user_nicename || '' : item.author;
		}

		return {
			slug: item.slug,
			name: decodeHtml( item.name ),
			meta: author ? strings.byAuthor.replace( '%s', decodeHtml( String( author ) ) ) : '',
			desc: decodeHtml( item.description || '' ),
			iconUrl: resizedImageUrl( screenshot, 112 ),
			themeUrl: themeUrl( item.slug ),
			previewLink: themePreviewUrl( item.slug ),
			previewLabel: strings.themePreview,
			previewKind: 'theme',
			type: 'theme',
		};
	}

	function apiUrl( tab, query ) {
		const base = tab === 'plugins'
			? 'https://api.wordpress.org/plugins/info/1.2/?action=query_plugins'
			: 'https://api.wordpress.org/themes/info/1.2/?action=query_themes';
		const params = new URLSearchParams();
		const trimmed = ( query || '' ).trim();

		params.set( 'request[per_page]', '24' );

		if ( tab === 'themes' ) {
			params.set( 'request[fields][screenshot_url]', '1' );
			params.set( 'request[fields][description]', '1' );
		} else {
			params.set( 'request[fields][short_description]', '1' );
			params.set( 'request[fields][icons]', '1' );
			params.set( 'request[fields][active_installs]', '1' );
			params.set( 'request[fields][preview_link]', '1' );
			params.set( 'request[fields][last_updated]', '1' );
		}

		if ( trimmed ) {
			params.set( 'request[search]', trimmed );
		} else {
			params.set( 'request[browse]', tab === 'plugins' ? 'featured' : 'popular' );
		}

		return base + '&' + params.toString();
	}

	function fallback( tab, query ) {
		const source = tab === 'plugins' ? config.fallback.plugins : config.fallback.themes;
		const needle = ( query || '' ).trim().toLowerCase();
		const filtered = needle
			? source.filter( ( item ) => ( item.name + ' ' + item.slug ).toLowerCase().indexOf( needle ) >= 0 )
			: source;

		return filtered.map( ( item ) => ( {
			slug: item.slug,
			name: item.name,
			meta: strings.wordpressOrg,
			desc: item.desc,
			iconUrl: '',
			pluginUrl: tab === 'plugins' ? pluginUrl( item.slug ) : '',
			themeUrl: tab === 'themes' ? themeUrl( item.slug ) : '',
			previewLink: tab === 'themes' ? themePreviewUrl( item.slug ) : '',
			previewLabel: tab === 'themes' ? strings.themePreview : '',
			previewKind: '',
			type: tab === 'plugins' ? 'plugin' : 'theme',
		} ) );
	}

	function buildBlueprint() {
		const blueprint = {
			steps: [],
		};

		if ( currentLandingPage() !== config.defaultLandingPage ) {
			blueprint.landingPage = currentLandingPage();
		}

		if ( state.phpVersion !== config.defaultPhpVersion || state.wpVersion !== config.defaultWpVersion ) {
			blueprint.preferredVersions = {};

			if ( state.phpVersion !== config.defaultPhpVersion ) {
				blueprint.preferredVersions.php = state.phpVersion;
			}

			if ( state.wpVersion !== config.defaultWpVersion ) {
				blueprint.preferredVersions.wp = state.wpVersion;
			}
		}

		if ( state.language && state.language !== 'en_US' ) {
			blueprint.steps.push( {
				step: 'setSiteLanguage',
				language: state.language,
			} );
		}

		state.selectedPlugins.forEach( ( plugin ) => {
			const options = {
				activate: true,
			};

			if ( plugin.targetFolderName ) {
				options.targetFolderName = plugin.targetFolderName;
			}

			blueprint.steps.push( {
				step: 'installPlugin',
				pluginData: plugin.resourceData || {
					resource: 'wordpress.org/plugins',
					slug: plugin.slug,
				},
				options,
			} );
		} );

		state.selectedThemes.forEach( ( theme ) => {
			const options = {
				activate: true,
			};

			if ( theme.targetFolderName ) {
				options.targetFolderName = theme.targetFolderName;
			}

			blueprint.steps.push( {
				step: 'installTheme',
				themeData: theme.resourceData || {
					resource: 'wordpress.org/themes',
					slug: theme.slug,
				},
				options,
			} );
		} );

		return blueprint;
	}

	function currentLink() {
		if ( effectiveLinkMode() === 'blueprint' ) {
			return blueprintUrl();
		}

		return queryApiUrl();
	}

	function blueprintUrl() {
		const dataUrl = 'data:application/json,' + encodeURIComponent( JSON.stringify( buildBlueprint() ) );
		return 'https://playground.wordpress.net/?blueprint-url=' + dataUrl;
	}

	function queryApiUrl() {
		if ( isDefaultPlayground() ) {
			return 'https://playground.wordpress.net/';
		}

		const params = new URLSearchParams();

		state.selectedPlugins.forEach( ( plugin ) => {
			params.append( 'plugin', plugin.slug );
		} );

		if ( state.selectedThemes.length > 0 ) {
			params.set( 'theme', state.selectedThemes[0].slug );
		}

		if ( state.language !== config.defaultLanguage ) {
			params.set( 'language', state.language );
		}

		if ( state.wpVersion !== config.defaultWpVersion ) {
			params.set( 'wp', state.wpVersion );
		}

		if ( state.phpVersion !== config.defaultPhpVersion ) {
			params.set( 'php', state.phpVersion );
		}

		if ( currentLandingPage() !== config.defaultLandingPage ) {
			params.set( 'url', currentLandingPage() );
		}

		return 'https://playground.wordpress.net/?' + params.toString();
	}

	function effectiveLinkMode() {
		return requiresBlueprint() ? 'blueprint' : state.linkMode;
	}

	function requiresBlueprint() {
		return state.selectedPlugins.some( ( plugin ) => plugin.isCustom ) || state.selectedThemes.some( ( theme ) => theme.isCustom );
	}

	function isDefaultPlayground() {
		return state.selectedPlugins.length === 0 &&
			state.selectedThemes.length === 0 &&
			state.language === config.defaultLanguage &&
			state.wpVersion === config.defaultWpVersion &&
			state.phpVersion === config.defaultPhpVersion &&
			currentLandingPage() === config.defaultLandingPage;
	}

	function currentLandingPage() {
		if ( state.landingPage !== 'custom' ) {
			return state.landingPage;
		}

		const value = state.customLandingPage.trim();

		if ( value === '' ) {
			return '/';
		}

		if ( value.indexOf( 'http://' ) === 0 || value.indexOf( 'https://' ) === 0 || value.charAt( 0 ) === '/' ) {
			return value;
		}

		return '/' + value;
	}

	function text( key, count ) {
		if ( count === 1 && strings[ key + 'Singular' ] ) {
			return strings[ key + 'Singular' ].replace( '%d', count );
		}

		return strings[ key + 'Plural' ].replace( '%d', count );
	}

	function itemLetter( item ) {
		return ( item.name || '?' ).charAt( 0 ).toUpperCase();
	}

	function renderIcon( item ) {
		if ( item.iconUrl ) {
			const image = document.createElement( 'img' );
			image.className = 'playground-builder__thumb';
			image.src = item.iconUrl;
			image.alt = '';
			image.loading = 'lazy';
			return image;
		}

		const letter = document.createElement( 'div' );
		letter.className = 'playground-builder__letter';
		letter.textContent = itemLetter( item );

		return letter;
	}

	function renderResults() {
		els.results.replaceChildren();

		if ( state.loading ) {
			const wrapper = document.createElement( 'div' );
			const spinner = document.createElement( 'div' );
			const label = document.createElement( 'span' );
			wrapper.className = 'playground-builder__loading';
			spinner.className = 'playground-builder__spinner';
			label.textContent = strings.searching;
			wrapper.append( spinner, label );
			els.results.append( wrapper );
			return;
		}

		if ( state.error ) {
			const error = document.createElement( 'div' );
			error.className = 'playground-builder__state playground-builder__state--error';
			error.textContent = state.error;
			els.results.append( error );
			return;
		}

		if ( state.rawResults.length === 0 ) {
			const empty = document.createElement( 'div' );
			empty.className = 'playground-builder__state';
			empty.textContent = state.query.trim() !== state.searchedQuery.trim() ? strings.searchReady : strings.noMatches;
			els.results.append( empty );
			return;
		}

		const selectedSlugs = ( state.tab === 'plugins' ? state.selectedPlugins : state.selectedThemes ).map( ( item ) => item.slug );

		state.rawResults.forEach( ( item ) => {
			const added = selectedSlugs.indexOf( item.slug ) >= 0;
			const row = document.createElement( 'div' );
			const body = document.createElement( 'div' );
			const title = document.createElement( 'div' );
			const meta = document.createElement( 'div' );
			const desc = document.createElement( 'div' );
			const actions = document.createElement( 'div' );
			const button = document.createElement( 'button' );

			row.className = 'playground-builder__item';
			body.className = 'playground-builder__item-body';
			title.className = 'playground-builder__item-title';
			meta.className = 'playground-builder__item-meta';
			desc.className = 'playground-builder__item-desc';
			actions.className = 'playground-builder__item-actions';
			button.className = 'playground-builder__add';

			const itemUrl = directoryUrl( item );

			if ( itemUrl ) {
				const titleLink = document.createElement( 'a' );
				titleLink.href = itemUrl;
				titleLink.target = '_blank';
				titleLink.rel = 'noreferrer';
				titleLink.textContent = item.name;
				title.append( titleLink );
			} else {
				title.textContent = item.name;
			}

			meta.textContent = item.meta;
			desc.textContent = item.desc;
			button.type = 'button';
			button.textContent = item.type === 'theme'
				? ( added ? strings.usingTheme : strings.useTheme )
				: ( added ? strings.added : strings.add );
			button.disabled = added;
			body.append( title, meta, desc );

			if ( item.previewLink ) {
				const previewNote = document.createElement( 'div' );
				const preview = document.createElement( 'a' );
				previewNote.className = 'playground-builder__item-preview';
				previewNote.classList.toggle( 'playground-builder__item-preview--author', item.previewKind === 'author' );
				preview.href = item.previewLink;
				preview.target = '_blank';
				preview.rel = 'noreferrer';
				preview.textContent = item.previewLabel || strings.playgroundPreview;
				previewNote.append( preview );
				body.append( previewNote );
			}

			if ( ! added ) {
				button.addEventListener( 'click', () => addItem( item ) );
			}

			actions.append( button );
			row.append( renderIcon( item ), body, actions );
			els.results.append( row );
		} );
	}

	function renderSelected() {
		els.selected.replaceChildren();

		const selected = [ ...state.selectedPlugins, ...state.selectedThemes ];

		if ( state.selectedThemes.length === 0 ) {
			selected.push( defaultThemeItem() );
		}

		selected.forEach( ( item ) => {
			const row = document.createElement( 'div' );
			const body = document.createElement( 'div' );
			const name = document.createElement( 'div' );
			const kind = document.createElement( 'div' );
			const remove = document.createElement( 'button' );

			row.className = 'playground-builder__selected';
			body.className = 'playground-builder__item-body';
			name.className = 'playground-builder__selected-name';
			kind.className = 'playground-builder__selected-kind';
			remove.className = 'playground-builder__remove';

			const itemUrl = directoryUrl( item );

			if ( itemUrl ) {
				const nameLink = document.createElement( 'a' );
				nameLink.href = itemUrl;
				nameLink.target = '_blank';
				nameLink.rel = 'noreferrer';
				nameLink.textContent = item.name;
				name.append( nameLink );
			} else {
				name.textContent = item.name;
			}

			kind.textContent = item.meta || ( item.isDefault ? strings.defaultThemeKind : ( item.type === 'plugin' ? strings.plugin : strings.theme ) );
			remove.type = 'button';
			remove.textContent = 'x';
			remove.setAttribute( 'aria-label', strings.removeItem.replace( '%s', item.name ) );
			remove.hidden = item.isDefault;

			if ( ! item.isDefault ) {
				remove.addEventListener( 'click', () => removeItem( item.slug, item.type ) );
			}

			body.append( name, kind );
			row.append( renderIcon( item, true ), body, remove );
			els.selected.append( row );
		} );
	}

	function renderSummary() {
		const pluginCount = state.selectedPlugins.length;
		const blueprint = buildBlueprint();
		const link = currentLink();
		const themeSummary = state.selectedThemes.length > 0 ? strings.selectedThemeSummary : strings.defaultThemeSummary;
		const linkMode = effectiveLinkMode();

		els.selectionSummary.textContent = strings.selectionSummary
			.replace( '%1$s', text( 'pluginCount', pluginCount ) )
			.replace( '%2$s', themeSummary );
		renderFormatCaption( linkMode );
		els.link.textContent = link;
		els.open.href = link;
		els.json.textContent = JSON.stringify( blueprint, null, 2 );
		els.json.classList.toggle( 'is-visible', state.showJson );
		els.jsonToggle.textContent = state.showJson ? strings.hideJson : strings.viewJson;
		els.copy.textContent = state.copied ? strings.copied : strings.copyLink;
		els.copy.classList.toggle( 'is-copied', state.copied );
		els.customLandingPage.hidden = state.landingPage !== 'custom';
	}

	function renderFormatCaption( linkMode ) {
		els.buildSummary.replaceChildren();

		if ( requiresBlueprint() ) {
			els.buildSummary.textContent = strings.blueprintRequired;
			return;
		}

		const button = document.createElement( 'button' );
		button.type = 'button';
		button.className = 'playground-builder__caption-link';
		button.textContent = linkMode === 'blueprint' ? strings.switchToQueryApi : strings.switchToBlueprint;
		button.addEventListener( 'click', () => {
			state.linkMode = linkMode === 'blueprint' ? 'query' : 'blueprint';
			state.copied = false;
			renderSummary();
		} );
		els.buildSummary.append( button );
	}

	function defaultThemeItem() {
		const theme = config.defaultThemes[ state.wpVersion ] || config.defaultThemes.latest;

		return {
			slug: theme.slug,
			name: theme.name,
			iconUrl: '',
			themeUrl: themeUrl( theme.slug ),
			type: 'theme',
			isDefault: true,
		};
	}

	function renderTabs() {
		els.tabs.forEach( ( tab ) => {
			const active = tab.dataset.tab === state.tab;
			tab.setAttribute( 'aria-selected', active ? 'true' : 'false' );
		} );

		els.query.placeholder = state.tab === 'plugins' ? strings.searchPluginsPlaceholder : strings.searchThemesPlaceholder;
		els.featuredInfo.hidden = state.tab !== 'plugins' || state.query.trim() !== '';
	}

	function render() {
		renderTabs();
		renderResults();
		renderSelected();
		renderSummary();
	}

	async function runSearch() {
		const tab = state.tab;
		const query = state.query;
		const customResult = customUrlResult( query, tab );
		state.searchedQuery = query;
		state.loading = true;
		state.error = '';
		render();

		if ( customResult ) {
			state.rawResults = [ customResult ];
			state.loading = false;
			render();
			return;
		}

		try {
			const response = await fetch( apiUrl( tab, query ) );

			if ( ! response.ok ) {
				throw new Error( 'HTTP ' + response.status );
			}

			const data = await response.json();
			const list = tab === 'plugins' ? data.plugins || [] : data.themes || [];

			state.rawResults = list.map( ( item ) => normalize( item, tab ) );
			state.loading = false;
		} catch ( error ) {
			const fallbackResults = fallback( tab, query );
			state.rawResults = fallbackResults;
			state.loading = false;
			state.error = fallbackResults.length ? '' : strings.connectionError;
		}

		render();
	}

	function addItem( item ) {
		const key = item.type === 'plugin' ? 'selectedPlugins' : 'selectedThemes';

		if ( state[ key ].some( ( selected ) => selected.slug === item.slug ) ) {
			return;
		}

		const selectedItem = {
			slug: item.slug,
			name: item.name,
			iconUrl: item.iconUrl,
			meta: item.meta,
			pluginUrl: item.pluginUrl,
			themeUrl: item.themeUrl,
			type: item.type,
			isCustom: item.isCustom,
			resourceData: item.resourceData,
			targetFolderName: item.targetFolderName,
		};

		if ( item.type === 'theme' ) {
			state.selectedThemes = [ selectedItem ];
		} else {
			state[ key ].push( selectedItem );
		}

		if ( item.isCustom ) {
			state.linkMode = 'blueprint';
		}

		state.copied = false;
		render();
	}

	function removeItem( slug, type ) {
		const key = type === 'plugin' ? 'selectedPlugins' : 'selectedThemes';
		state[ key ] = state[ key ].filter( ( item ) => item.slug !== slug );
		state.copied = false;
		render();
	}

	function copyLink() {
		const done = () => {
			state.copied = true;
			render();
			clearTimeout( state.copyTimer );
			state.copyTimer = setTimeout( () => {
				state.copied = false;
				render();
			}, 2000 );
		};

		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			navigator.clipboard.writeText( currentLink() ).then( done ).catch( done );
			return;
		}

		done();
	}

	els.tabs.forEach( ( tab ) => {
		tab.addEventListener( 'click', () => {
			if ( tab.dataset.tab === state.tab ) {
				return;
			}

			state.tab = tab.dataset.tab;
			state.rawResults = [];
			runSearch();
		} );
	} );

	els.form.addEventListener( 'submit', ( event ) => {
		event.preventDefault();
		state.query = els.query.value;
		runSearch();
	} );

	els.query.addEventListener( 'input', () => {
		state.query = els.query.value;
		const customResult = customUrlResult( state.query, state.tab );

		if ( customResult ) {
			clearTimeout( state.searchTimer );
			state.searchedQuery = state.query;
			state.rawResults = [ customResult ];
			state.error = '';
			state.loading = false;
			render();
			return;
		}

		state.rawResults = [];
		state.error = '';
		state.loading = false;
		renderTabs();
		renderResults();

		clearTimeout( state.searchTimer );

		if ( state.query.trim() !== '' ) {
			state.searchTimer = setTimeout( () => runSearch(), 900 );
		}
	} );

	els.language.addEventListener( 'change', () => {
		state.language = els.language.value;
		state.copied = false;
		renderSummary();
	} );

	els.wpVersion.addEventListener( 'change', () => {
		state.wpVersion = els.wpVersion.value;
		state.copied = false;
		renderSelected();
		renderSummary();
	} );

	els.phpVersion.addEventListener( 'change', () => {
		state.phpVersion = els.phpVersion.value;
		state.copied = false;
		renderSummary();
	} );

	els.landingPage.addEventListener( 'change', () => {
		state.landingPage = els.landingPage.value;
		state.copied = false;
		renderSummary();
	} );

	els.customLandingPage.addEventListener( 'input', () => {
		state.customLandingPage = els.customLandingPage.value;
		state.copied = false;
		renderSummary();
	} );

	els.copy.addEventListener( 'click', copyLink );
	els.jsonToggle.addEventListener( 'click', () => {
		state.showJson = ! state.showJson;
		renderSummary();
	} );

	els.landingPage.value = state.landingPage;
	render();
	runSearch();
}() );
