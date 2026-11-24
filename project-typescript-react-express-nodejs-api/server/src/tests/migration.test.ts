import assert from 'node:assert/strict';
import { test } from 'node:test';
import { auditMigration } from '../migration/audit';

test('published and explicitly archived PHP sources retain their migration provenance', () => {
  const audit = auditMigration();
  assert.ok(audit.totalPhpFiles >= 200);
  assert.ok(audit.functionalFiles > 180);
  assert.deepEqual(audit.unmapped, []);
  assert.deepEqual(audit.missingTargets, []);
  assert.equal(audit.functionalFiles + audit.referenceOrNonfunctionalFiles, audit.totalPhpFiles);
  assert.equal(audit.publishedPhpFiles + audit.archivedPhpFiles, audit.totalPhpFiles);
  assert.equal(audit.files.filter(file => file.sourceStatus === 'published').length, audit.publishedPhpFiles);
  assert.equal(audit.files.filter(file => file.sourceStatus === 'archived-unused').length, audit.archivedPhpFiles);
  assert.ok(audit.files.every(file => /^[a-f0-9]{64}$/.test(file.sha256)));
  assert.ok(audit.files.find(file => file.source === 'php/app/manage.php')!.legacyEvents.length > 100);
});
