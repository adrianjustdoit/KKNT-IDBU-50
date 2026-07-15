/* ================================================
   3D PRODUCT VIEWER — Three.js Interactive Models
   KKN Rowosari 3R Website
   ================================================ */

import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

// =================== INITIALIZATION ===================
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('product3dCanvas');
    if (!canvas) return;

    const modelType = canvas.dataset.modelType;
    const container = canvas.parentElement;

    // Scene setup
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0a0f0d);
    scene.fog = new THREE.FogExp2(0x0a0f0d, 0.015);

    // Camera
    const camera = new THREE.PerspectiveCamera(
        45,
        container.clientWidth / container.clientHeight,
        0.1,
        1000
    );
    camera.position.set(4, 3, 5);

    // Renderer
    const renderer = new THREE.WebGLRenderer({
        canvas,
        antialias: true,
        alpha: true,
    });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;

    // Controls
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.maxDistance = 12;
    controls.minDistance = 2;
    controls.autoRotate = true;
    controls.autoRotateSpeed = 1.5;
    controls.enablePan = false;
    controls.maxPolarAngle = Math.PI * 0.85;

    // Stop auto-rotate when user interacts
    controls.addEventListener('start', () => {
        controls.autoRotate = false;
    });

    // Resume auto-rotate after idle
    let idleTimer = null;
    controls.addEventListener('end', () => {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(() => {
            controls.autoRotate = true;
        }, 4000);
    });

    // =================== LIGHTING ===================
    setupLighting(scene);

    // =================== GROUND PLANE ===================
    setupGround(scene);

    // =================== FLOATING PARTICLES ===================
    const particles = createParticles(scene);

    // =================== BUILD 3D MODEL ===================
    let model = null;
    switch (modelType) {
        case 'kompos':
            model = createKomposModel(scene);
            break;
        case 'terrazzo':
            model = createTerrazzoModel(scene);
            break;
        case 'keychain':
            model = createKeychainModel(scene);
            break;
        default:
            model = createDefaultModel(scene);
    }

    // =================== ANIMATION LOOP ===================
    const clock = new THREE.Clock();

    function animate() {
        requestAnimationFrame(animate);
        const elapsed = clock.getElapsedTime();

        controls.update();

        // Animate particles
        if (particles) {
            particles.rotation.y = elapsed * 0.05;
            particles.position.y = Math.sin(elapsed * 0.3) * 0.1;
        }

        // Animate model
        if (model) {
            model.position.y = Math.sin(elapsed * 0.8) * 0.05 + model.userData.baseY;
        }

        renderer.render(scene, camera);
    }

    animate();

    // =================== RESIZE HANDLER ===================
    window.addEventListener('resize', () => {
        const width = container.clientWidth;
        const height = container.clientHeight;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    });

    // =================== LOADING ANIMATION ===================
    hideLoader();

    // =================== COMPOSITION CHART ANIMATION ===================
    initCompositionAnimation();
});

// =================== LIGHTING SETUP ===================
function setupLighting(scene) {
    // Ambient light
    const ambient = new THREE.AmbientLight(0xffffff, 0.4);
    scene.add(ambient);

    // Main directional light
    const mainLight = new THREE.DirectionalLight(0xfff5e6, 1.2);
    mainLight.position.set(5, 8, 5);
    mainLight.castShadow = true;
    mainLight.shadow.mapSize.width = 1024;
    mainLight.shadow.mapSize.height = 1024;
    mainLight.shadow.camera.near = 0.5;
    mainLight.shadow.camera.far = 20;
    mainLight.shadow.camera.left = -5;
    mainLight.shadow.camera.right = 5;
    mainLight.shadow.camera.top = 5;
    mainLight.shadow.camera.bottom = -5;
    scene.add(mainLight);

    // Fill light (green tint for eco feel)
    const fillLight = new THREE.DirectionalLight(0x4a7c59, 0.5);
    fillLight.position.set(-3, 4, -2);
    scene.add(fillLight);

    // Rim light
    const rimLight = new THREE.DirectionalLight(0xc4a66a, 0.4);
    rimLight.position.set(0, 2, -5);
    scene.add(rimLight);

    // Point light for glow
    const pointLight = new THREE.PointLight(0x4a7c59, 0.8, 10);
    pointLight.position.set(0, 3, 0);
    scene.add(pointLight);
}

// =================== GROUND PLANE ===================
function setupGround(scene) {
    const groundGeo = new THREE.CircleGeometry(8, 64);
    const groundMat = new THREE.MeshStandardMaterial({
        color: 0x0d1410,
        roughness: 0.9,
        metalness: 0.1,
    });
    const ground = new THREE.Mesh(groundGeo, groundMat);
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = -0.5;
    ground.receiveShadow = true;
    scene.add(ground);

    // Subtle grid ring
    const ringGeo = new THREE.RingGeometry(2.5, 2.52, 64);
    const ringMat = new THREE.MeshBasicMaterial({
        color: 0x4a7c59,
        transparent: true,
        opacity: 0.15,
        side: THREE.DoubleSide,
    });
    const ring = new THREE.Mesh(ringGeo, ringMat);
    ring.rotation.x = -Math.PI / 2;
    ring.position.y = -0.49;
    scene.add(ring);
}

// =================== FLOATING PARTICLES ===================
function createParticles(scene) {
    const particleCount = 200;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);

    const green = new THREE.Color(0x4a7c59);
    const amber = new THREE.Color(0xc4a66a);
    const white = new THREE.Color(0xffffff);

    for (let i = 0; i < particleCount; i++) {
        const i3 = i * 3;
        const radius = 3 + Math.random() * 6;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.random() * Math.PI;

        positions[i3] = radius * Math.sin(phi) * Math.cos(theta);
        positions[i3 + 1] = (Math.random() - 0.3) * 5;
        positions[i3 + 2] = radius * Math.sin(phi) * Math.sin(theta);

        const colorChoice = Math.random();
        const color = colorChoice < 0.4 ? green : colorChoice < 0.7 ? amber : white;
        colors[i3] = color.r;
        colors[i3 + 1] = color.g;
        colors[i3 + 2] = color.b;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({
        size: 0.03,
        vertexColors: true,
        transparent: true,
        opacity: 0.6,
        blending: THREE.AdditiveBlending,
    });

    const particles = new THREE.Points(geometry, material);
    scene.add(particles);
    return particles;
}

// =================== KOMPOS MODEL ===================
function createKomposModel(scene) {
    const group = new THREE.Group();

    // Main bag body — tapered cylinder
    const bagGeo = new THREE.CylinderGeometry(0.7, 0.85, 1.8, 16, 8);
    // Add slight irregularity to vertices for organic look
    const bagPos = bagGeo.attributes.position;
    for (let i = 0; i < bagPos.count; i++) {
        const x = bagPos.getX(i);
        const y = bagPos.getY(i);
        const z = bagPos.getZ(i);
        const noise = (Math.sin(x * 8) * Math.cos(z * 8) * Math.sin(y * 4)) * 0.03;
        bagPos.setX(i, x + noise);
        bagPos.setZ(i, z + noise);
    }
    bagGeo.computeVertexNormals();

    const bagMat = new THREE.MeshStandardMaterial({
        color: 0x8B6914,
        roughness: 0.85,
        metalness: 0.0,
    });
    const bag = new THREE.Mesh(bagGeo, bagMat);
    bag.position.y = 0.4;
    bag.castShadow = true;
    bag.receiveShadow = true;
    group.add(bag);

    // Bag opening — torus at top showing compost
    const openingGeo = new THREE.TorusGeometry(0.65, 0.08, 8, 24);
    const openingMat = new THREE.MeshStandardMaterial({
        color: 0x6B4E0A,
        roughness: 0.9,
    });
    const opening = new THREE.Mesh(openingGeo, openingMat);
    opening.position.y = 1.3;
    opening.rotation.x = Math.PI / 2;
    group.add(opening);

    // Compost fill — visible at the top
    const compostGeo = new THREE.CylinderGeometry(0.63, 0.63, 0.15, 16);
    const compostMat = new THREE.MeshStandardMaterial({
        color: 0x3E2723,
        roughness: 1.0,
        metalness: 0.0,
    });
    const compost = new THREE.Mesh(compostGeo, compostMat);
    compost.position.y = 1.22;
    group.add(compost);

    // Compost particles on top
    for (let i = 0; i < 30; i++) {
        const size = 0.02 + Math.random() * 0.04;
        const pGeo = new THREE.SphereGeometry(size, 6, 6);
        const pMat = new THREE.MeshStandardMaterial({
            color: new THREE.Color().setHSL(
                0.08 + Math.random() * 0.06,
                0.6 + Math.random() * 0.3,
                0.15 + Math.random() * 0.2
            ),
            roughness: 1.0,
        });
        const p = new THREE.Mesh(pGeo, pMat);
        const angle = Math.random() * Math.PI * 2;
        const radius = Math.random() * 0.55;
        p.position.set(
            Math.cos(angle) * radius,
            1.28 + Math.random() * 0.05,
            Math.sin(angle) * radius
        );
        group.add(p);
    }

    // Leaf decorations
    for (let i = 0; i < 5; i++) {
        const leafGeo = new THREE.SphereGeometry(0.06, 4, 4);
        leafGeo.scale(1, 0.3, 2);
        const leafMat = new THREE.MeshStandardMaterial({
            color: 0x228B22,
            roughness: 0.8,
        });
        const leaf = new THREE.Mesh(leafGeo, leafMat);
        const angle = Math.random() * Math.PI * 2;
        leaf.position.set(
            Math.cos(angle) * 0.5,
            1.32 + Math.random() * 0.03,
            Math.sin(angle) * 0.5
        );
        leaf.rotation.y = Math.random() * Math.PI;
        group.add(leaf);
    }

    // Label on bag
    const labelGeo = new THREE.PlaneGeometry(0.8, 0.45);
    const labelCanvas = createLabelTexture('KOMPOS\nORGANIK', '#4a7c59', '#faf6f0');
    const labelTexture = new THREE.CanvasTexture(labelCanvas);
    const labelMat = new THREE.MeshStandardMaterial({
        map: labelTexture,
        roughness: 0.7,
    });
    const label = new THREE.Mesh(labelGeo, labelMat);
    label.position.set(0, 0.5, 0.86);
    group.add(label);

    // Tie at top — rope
    const tieGeo = new THREE.TorusGeometry(0.12, 0.025, 8, 16);
    const tieMat = new THREE.MeshStandardMaterial({
        color: 0x8B7355,
        roughness: 0.9,
    });
    const tie = new THREE.Mesh(tieGeo, tieMat);
    tie.position.set(0.3, 1.35, 0);
    tie.rotation.x = Math.PI / 4;
    group.add(tie);

    // Small scattered soil on ground
    for (let i = 0; i < 15; i++) {
        const soilGeo = new THREE.SphereGeometry(0.015 + Math.random() * 0.025, 5, 5);
        const soilMat = new THREE.MeshStandardMaterial({
            color: new THREE.Color().setHSL(0.07, 0.7, 0.12 + Math.random() * 0.1),
            roughness: 1,
        });
        const soil = new THREE.Mesh(soilGeo, soilMat);
        const dist = 0.8 + Math.random() * 0.6;
        const angle = Math.random() * Math.PI * 2;
        soil.position.set(
            Math.cos(angle) * dist,
            -0.48,
            Math.sin(angle) * dist
        );
        group.add(soil);
    }

    group.userData.baseY = 0;
    scene.add(group);
    return group;
}

// =================== TERRAZZO MODEL ===================
function createTerrazzoModel(scene) {
    const group = new THREE.Group();

    // Main terrazzo slab
    const slabGeo = new THREE.CylinderGeometry(1.3, 1.3, 0.25, 32);
    const slabMat = new THREE.MeshStandardMaterial({
        color: 0xd4cdc5,
        roughness: 0.3,
        metalness: 0.05,
    });
    const slab = new THREE.Mesh(slabGeo, slabMat);
    slab.position.y = 0.125;
    slab.castShadow = true;
    slab.receiveShadow = true;
    group.add(slab);

    // Terrazzo chips embedded in slab surface
    const chipColors = [
        0x87CEEB, // glass blue
        0xCD853F, // ceramic brown
        0xFF6347, // plastic red
        0x98FB98, // green glass
        0xDDA0DD, // purple
        0xFFD700, // golden
        0x4a7c59, // eco green
        0xf0e8d8, // cream
        0x808080, // grey
        0xB22222, // dark red
    ];

    for (let i = 0; i < 80; i++) {
        const angle = Math.random() * Math.PI * 2;
        const radius = Math.random() * 1.15;
        const chipSize = 0.04 + Math.random() * 0.08;

        // Random shape: some round, some angular
        let chipGeo;
        const shapeRng = Math.random();
        if (shapeRng < 0.4) {
            chipGeo = new THREE.SphereGeometry(chipSize, 5, 5);
            chipGeo.scale(1 + Math.random() * 0.5, 0.3, 1 + Math.random() * 0.5);
        } else if (shapeRng < 0.7) {
            chipGeo = new THREE.BoxGeometry(
                chipSize * (1 + Math.random()),
                chipSize * 0.5,
                chipSize * (1 + Math.random())
            );
        } else {
            chipGeo = new THREE.CylinderGeometry(chipSize, chipSize, chipSize * 0.4, 5 + Math.floor(Math.random() * 3));
        }

        const color = chipColors[Math.floor(Math.random() * chipColors.length)];
        const chipMat = new THREE.MeshStandardMaterial({
            color: color,
            roughness: 0.25 + Math.random() * 0.3,
            metalness: Math.random() * 0.1,
        });
        const chip = new THREE.Mesh(chipGeo, chipMat);
        chip.position.set(
            Math.cos(angle) * radius,
            0.2 + Math.random() * 0.04,
            Math.sin(angle) * radius
        );
        chip.rotation.set(
            Math.random() * 0.3,
            Math.random() * Math.PI * 2,
            Math.random() * 0.3
        );
        group.add(chip);
    }

    // Polish/gloss layer on top
    const glossGeo = new THREE.CylinderGeometry(1.3, 1.3, 0.01, 32);
    const glossMat = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        transparent: true,
        opacity: 0.08,
        roughness: 0.0,
        metalness: 0.3,
    });
    const gloss = new THREE.Mesh(glossGeo, glossMat);
    gloss.position.y = 0.25;
    group.add(gloss);

    // Edge bevel ring
    const edgeGeo = new THREE.TorusGeometry(1.3, 0.02, 8, 64);
    const edgeMat = new THREE.MeshStandardMaterial({
        color: 0xa0a0a0,
        roughness: 0.4,
        metalness: 0.3,
    });
    const edgeTop = new THREE.Mesh(edgeGeo, edgeMat);
    edgeTop.position.y = 0.25;
    edgeTop.rotation.x = Math.PI / 2;
    group.add(edgeTop);

    const edgeBottom = new THREE.Mesh(edgeGeo, edgeMat);
    edgeBottom.position.y = 0.0;
    edgeBottom.rotation.x = Math.PI / 2;
    group.add(edgeBottom);

    // Second smaller terrazzo piece tilted beside the main one
    const slab2Geo = new THREE.CylinderGeometry(0.6, 0.6, 0.2, 6);
    const slab2Mat = new THREE.MeshStandardMaterial({
        color: 0xc8c0b8,
        roughness: 0.35,
        metalness: 0.05,
    });
    const slab2 = new THREE.Mesh(slab2Geo, slab2Mat);
    slab2.position.set(1.6, 0.15, 0.8);
    slab2.rotation.z = 0.15;
    slab2.castShadow = true;
    group.add(slab2);

    // Chips on second piece
    for (let i = 0; i < 15; i++) {
        const chipSize = 0.02 + Math.random() * 0.04;
        const chipGeo2 = new THREE.SphereGeometry(chipSize, 4, 4);
        chipGeo2.scale(1, 0.3, 1);
        const color = chipColors[Math.floor(Math.random() * chipColors.length)];
        const chip2 = new THREE.Mesh(chipGeo2, new THREE.MeshStandardMaterial({
            color, roughness: 0.3,
        }));
        const a2 = Math.random() * Math.PI * 2;
        const r2 = Math.random() * 0.45;
        chip2.position.set(
            1.6 + Math.cos(a2) * r2,
            0.24,
            0.8 + Math.sin(a2) * r2
        );
        group.add(chip2);
    }

    group.position.y = -0.15;
    group.userData.baseY = -0.15;
    scene.add(group);
    return group;
}

// =================== KEYCHAIN MODEL ===================
function createKeychainModel(scene) {
    const group = new THREE.Group();

    // Main ring
    const ringGeo = new THREE.TorusGeometry(0.3, 0.04, 12, 32);
    const ringMat = new THREE.MeshStandardMaterial({
        color: 0xc0c0c0,
        roughness: 0.2,
        metalness: 0.9,
    });
    const ring = new THREE.Mesh(ringGeo, ringMat);
    ring.position.y = 1.6;
    ring.castShadow = true;
    group.add(ring);

    // Chain links
    for (let i = 0; i < 3; i++) {
        const linkGeo = new THREE.TorusGeometry(0.08, 0.02, 8, 16);
        const link = new THREE.Mesh(linkGeo, ringMat);
        link.position.y = 1.3 - i * 0.15;
        link.rotation.x = i % 2 === 0 ? 0 : Math.PI / 2;
        group.add(link);
    }

    // Main charm body — recycled resin pendant
    const charmGroup = new THREE.Group();

    // Rounded rectangle charm (using extruded shape)
    const charmShape = new THREE.Shape();
    const w = 0.6, h = 0.8, r = 0.12;
    charmShape.moveTo(-w / 2 + r, -h / 2);
    charmShape.lineTo(w / 2 - r, -h / 2);
    charmShape.quadraticCurveTo(w / 2, -h / 2, w / 2, -h / 2 + r);
    charmShape.lineTo(w / 2, h / 2 - r);
    charmShape.quadraticCurveTo(w / 2, h / 2, w / 2 - r, h / 2);
    charmShape.lineTo(-w / 2 + r, h / 2);
    charmShape.quadraticCurveTo(-w / 2, h / 2, -w / 2, h / 2 - r);
    charmShape.lineTo(-w / 2, -h / 2 + r);
    charmShape.quadraticCurveTo(-w / 2, -h / 2, -w / 2 + r, -h / 2);

    const extrudeSettings = {
        steps: 1,
        depth: 0.12,
        bevelEnabled: true,
        bevelThickness: 0.02,
        bevelSize: 0.02,
        bevelSegments: 3,
    };

    const charmGeo = new THREE.ExtrudeGeometry(charmShape, extrudeSettings);
    const charmMat = new THREE.MeshStandardMaterial({
        color: 0x2d8a5e,
        roughness: 0.3,
        metalness: 0.15,
        transparent: true,
        opacity: 0.85,
    });
    const charm = new THREE.Mesh(charmGeo, charmMat);
    charm.rotation.y = Math.PI;
    charm.position.z = 0.06;
    charmGroup.add(charm);

    // Recycle symbol on charm (3 arrows using simple geometry)
    const arrowMat = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        roughness: 0.5,
        metalness: 0.1,
    });

    for (let i = 0; i < 3; i++) {
        const arrowAngle = (i * Math.PI * 2) / 3 - Math.PI / 6;

        // Arrow shaft
        const shaftGeo = new THREE.BoxGeometry(0.04, 0.2, 0.03);
        const shaft = new THREE.Mesh(shaftGeo, arrowMat);
        shaft.position.set(
            Math.cos(arrowAngle) * 0.16,
            Math.sin(arrowAngle) * 0.16,
            0.15
        );
        shaft.rotation.z = arrowAngle - Math.PI / 2;
        charmGroup.add(shaft);

        // Arrow head
        const headGeo = new THREE.ConeGeometry(0.05, 0.08, 3);
        const head = new THREE.Mesh(headGeo, arrowMat);
        const headAngle = arrowAngle + 0.3;
        head.position.set(
            Math.cos(headAngle) * 0.25,
            Math.sin(headAngle) * 0.25,
            0.15
        );
        head.rotation.z = headAngle - Math.PI / 2;
        charmGroup.add(head);
    }

    // Embedded recycled material bits inside charm
    const bitColors = [0xFF69B4, 0x4169E1, 0xFFD700, 0xFF6347, 0x98FB98];
    for (let i = 0; i < 12; i++) {
        const bitSize = 0.015 + Math.random() * 0.025;
        const bitGeo = new THREE.SphereGeometry(bitSize, 4, 4);
        const bitMat = new THREE.MeshStandardMaterial({
            color: bitColors[Math.floor(Math.random() * bitColors.length)],
            roughness: 0.4,
            transparent: true,
            opacity: 0.7,
        });
        const bit = new THREE.Mesh(bitGeo, bitMat);
        bit.position.set(
            (Math.random() - 0.5) * 0.4,
            (Math.random() - 0.5) * 0.6,
            0.06 + Math.random() * 0.06
        );
        charmGroup.add(bit);
    }

    charmGroup.position.y = 0.55;
    group.add(charmGroup);

    // Connector between chain and charm
    const connGeo = new THREE.CylinderGeometry(0.015, 0.015, 0.25, 8);
    const conn = new THREE.Mesh(connGeo, ringMat);
    conn.position.y = 1.0;
    group.add(conn);

    // Small jump ring
    const jumpGeo = new THREE.TorusGeometry(0.06, 0.015, 8, 16);
    const jumpRing = new THREE.Mesh(jumpGeo, ringMat);
    jumpRing.position.y = 1.13;
    group.add(jumpRing);

    group.position.y = -0.6;
    group.userData.baseY = -0.6;
    scene.add(group);
    return group;
}

// =================== DEFAULT MODEL ===================
function createDefaultModel(scene) {
    const group = new THREE.Group();

    // Simple eco sphere
    const sphereGeo = new THREE.IcosahedronGeometry(1, 2);
    const sphereMat = new THREE.MeshStandardMaterial({
        color: 0x4a7c59,
        roughness: 0.5,
        metalness: 0.2,
        wireframe: false,
    });
    const sphere = new THREE.Mesh(sphereGeo, sphereMat);
    sphere.castShadow = true;
    group.add(sphere);

    // Wireframe overlay
    const wireMat = new THREE.MeshBasicMaterial({
        color: 0x6a9d76,
        wireframe: true,
        transparent: true,
        opacity: 0.15,
    });
    const wire = new THREE.Mesh(sphereGeo, wireMat);
    wire.scale.set(1.01, 1.01, 1.01);
    group.add(wire);

    group.userData.baseY = 0.5;
    group.position.y = 0.5;
    scene.add(group);
    return group;
}

// =================== HELPER: CREATE LABEL TEXTURE ===================
function createLabelTexture(text, bgColor, textColor) {
    const canvas = document.createElement('canvas');
    canvas.width = 256;
    canvas.height = 128;
    const ctx = canvas.getContext('2d');

    // Background
    ctx.fillStyle = bgColor;
    ctx.beginPath();
    const rx = 10;
    ctx.roundRect(4, 4, 248, 120, rx);
    ctx.fill();

    // Border
    ctx.strokeStyle = textColor;
    ctx.lineWidth = 2;
    ctx.stroke();

    // Text
    ctx.fillStyle = textColor;
    ctx.font = 'bold 28px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    const lines = text.split('\n');
    const lineHeight = 32;
    const startY = 64 - ((lines.length - 1) * lineHeight) / 2;
    lines.forEach((line, i) => {
        ctx.fillText(line, 128, startY + i * lineHeight);
    });

    return canvas;
}

// =================== LOADER ===================
function hideLoader() {
    const loader = document.getElementById('viewer3dLoader');
    if (loader) {
        setTimeout(() => {
            loader.classList.add('loaded');
            setTimeout(() => loader.remove(), 500);
        }, 800);
    }
}

// =================== COMPOSITION CHART ANIMATION ===================
function initCompositionAnimation() {
    const bars = document.querySelectorAll('.composition-bar__fill');
    if (!bars.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Stagger animation
                const items = entry.target.closest('.composition-list')?.querySelectorAll('.composition-item') || [];
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('animate-in');
                        const fill = item.querySelector('.composition-bar__fill');
                        if (fill) {
                            fill.style.width = fill.dataset.width;
                        }
                    }, index * 150);
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    bars.forEach(bar => observer.observe(bar));
}
